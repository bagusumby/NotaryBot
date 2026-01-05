<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\EventInput;
use App\Models\ChatUser;
use App\Models\Review;
use App\Models\Intent;
use App\Models\UnansweredQuestion;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
        ]);

        $sessionId = session()->getId();

        // Cek apakah user dengan session ini sudah ada
        $chatUser = ChatUser::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'last_activity' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'user' => $chatUser
        ]);
    }

    public function checkUser()
    {
        $sessionId = session()->getId();
        $chatUser = ChatUser::where('session_id', $sessionId)->first();

        return response()->json([
            'registered' => $chatUser ? true : false,
            'user' => $chatUser
        ]);
    }

    public function welcome()
    {
        try {
            $sessionId = session()->getId();

            $keyPath = storage_path('app/dialogflow/notarybot.json');
            
            if (!file_exists($keyPath)) {
                return response()->json([
                    'error' => 'Dialogflow key not found'
                ], 500);
            }

            $sessionsClient = new SessionsClient([
                'credentials' => $keyPath,
            ]);

            $projectId = 'notarybot-hsru';
            $session = $sessionsClient->sessionName($projectId, $sessionId);

            // Create EventInput for WELCOME event
            $eventInput = new EventInput();
            $eventInput->setName('WELCOME');
            $eventInput->setLanguageCode('id');

            // Create QueryInput
            $queryInput = new QueryInput();
            $queryInput->setEvent($eventInput);

            // Create DetectIntentRequest
            $detectIntentRequest = new DetectIntentRequest();
            $detectIntentRequest->setSession($session);
            $detectIntentRequest->setQueryInput($queryInput);

            // Send request to Dialogflow
            $response = $sessionsClient->detectIntent($detectIntentRequest);
            $queryResult = $response->getQueryResult();

            $sessionsClient->close();

            // Parse fulfillment messages
            $parsedPayload = [];
            foreach ($queryResult->getFulfillmentMessages() as $msg) {
                $parsedPayload[] = $this->parseMessage($msg);
            }

            return response()->json([
                'messages' => $parsedPayload
            ]);
        } catch (\Exception $e) {
            Log::error('Welcome event error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function send(Request $request)
    {
        try {
            $message = $request->message;
            $sessionId = session()->getId();

            // Update last activity
            ChatUser::where('session_id', $sessionId)->update([
                'last_activity' => now()
            ]);

            $keyPath = storage_path('app/dialogflow/notarybot.json');
            
            if (!file_exists($keyPath)) {
                return response()->json([
                    'error' => 'Dialogflow key not found',
                    'reply' => 'Maaf, sistem chatbot sedang dalam perbaikan.'
                ], 500);
            }

            $sessionsClient = new SessionsClient([
                'credentials' => $keyPath,
            ]);

            $projectId = 'notarybot-hsru';
            $session = $sessionsClient->sessionName($projectId, $sessionId);

            // Create TextInput
            $textInput = new TextInput();
            $textInput->setText($message);
            $textInput->setLanguageCode('id');

            // Create QueryInput
            $queryInput = new QueryInput();
            $queryInput->setText($textInput);

            // Create DetectIntentRequest
            $detectIntentRequest = new DetectIntentRequest();
            $detectIntentRequest->setSession($session);
            $detectIntentRequest->setQueryInput($queryInput);

            // Send request to Dialogflow
            $response = $sessionsClient->detectIntent($detectIntentRequest);
            $queryResult = $response->getQueryResult();

            // Track intent usage
            $intentDisplayName = $queryResult->getIntent()->getDisplayName();
            if ($intentDisplayName) {
                Intent::where('display_name', $intentDisplayName)->increment('usage_count');
            }

            // Get fulfillment text
            $fulfillmentText = $queryResult->getFulfillmentText();

            // Check if bot couldn't answer (fallback response)
            if (stripos($fulfillmentText, 'Mohon maaf, NotaryBot belum memahami pertanyaan Anda') !== false ||
                stripos($fulfillmentText, 'Maaf, saya belum memahami') !== false ||
                stripos($fulfillmentText, 'belum memahami') !== false) {
                
                // Get chat user
                $chatUser = ChatUser::where('session_id', $sessionId)->first();
                
                // Save unanswered question
                UnansweredQuestion::create([
                    'chat_user_id' => $chatUser ? $chatUser->id : null,
                    'session_id' => $sessionId,
                    'question' => $message,
                    'bot_response' => $fulfillmentText
                ]);
            }

            $sessionsClient->close();

            // Parse fulfillment messages
            $parsedPayload = [];
            foreach ($queryResult->getFulfillmentMessages() as $msg) {
                $parsedPayload[] = $this->parseMessage($msg);
            }

            return response()->json([
                'reply' => $fulfillmentText,
                'payload' => $parsedPayload
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'reply' => 'Maaf, terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }

    private function parseMessage($msg)
    {
        $parsed = [
            'type' => 'text',
            'text' => []
        ];

        if ($msg->hasText()) {
            foreach ($msg->getText()->getText() as $text) {
                $parsed['text'][] = $text;
            }
        } elseif ($msg->hasPayload()) {
            $payload = $msg->getPayload();
            $parsed['type'] = 'payload';
            $parsed['data'] = json_decode($payload->serializeToJsonString(), true);
        } elseif ($msg->hasQuickReplies()) {
            $parsed['type'] = 'quickReplies';
            $parsed['title'] = $msg->getQuickReplies()->getTitle();
            $parsed['replies'] = [];
            foreach ($msg->getQuickReplies()->getQuickReplies() as $reply) {
                $parsed['replies'][] = $reply;
            }
        } elseif ($msg->hasCard()) {
            $card = $msg->getCard();
            $parsed['type'] = 'card';
            $parsed['title'] = $card->getTitle();
            $parsed['subtitle'] = $card->getSubtitle();
            $parsed['imageUri'] = $card->getImageUri();
            $parsed['buttons'] = [];
            foreach ($card->getButtons() as $button) {
                $parsed['buttons'][] = [
                    'text' => $button->getText(),
                    'postback' => $button->getPostback()
                ];
            }
        }

        return $parsed;
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'rating' => 'required|in:positive,negative',
            'comment' => 'nullable|string|max:500'
        ]);

        $sessionId = session()->getId();
        $chatUser = ChatUser::where('session_id', $sessionId)->first();

        if (!$chatUser) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $review = Review::create([
            'chat_user_id' => $chatUser->id,
            'session_id' => $sessionId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas review Anda!',
            'review' => $review
        ]);
    }
}
