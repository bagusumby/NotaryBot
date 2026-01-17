<?php

namespace App\Http\Controllers;

use App\Models\Intent;
use App\Models\UnansweredQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Dialogflow\V2\Client\IntentsClient;
use Google\Cloud\Dialogflow\V2\ListIntentsRequest;
use Google\Cloud\Dialogflow\V2\CreateIntentRequest;
use Google\Cloud\Dialogflow\V2\UpdateIntentRequest;
use Google\Cloud\Dialogflow\V2\DeleteIntentRequest;
use Google\Cloud\Dialogflow\V2\IntentView;
use Google\Cloud\Dialogflow\V2\Intent as DialogflowIntent;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase\Part;
use Google\Cloud\Dialogflow\V2\Intent\Message;
use Google\Cloud\Dialogflow\V2\Intent\Message\Text;
use Google\Protobuf\FieldMask;

class IntentController extends Controller
{
    public function index()
    {
        // Auto-sync: Fetch langsung dari Dialogflow setiap kali halaman dibuka
        try {
            $keyPath = storage_path('app/dialogflow/notarybot.json');
            $intentsClient = new IntentsClient([
                'credentials' => $keyPath,
            ]);

            $projectId = env('DIALOGFLOW_PROJECT_ID');
            $parent = $intentsClient->projectAgentName($projectId);

            $listRequest = new ListIntentsRequest();
            $listRequest->setParent($parent);
            $listRequest->setIntentView(IntentView::INTENT_VIEW_FULL);

            $response = $intentsClient->listIntents($listRequest);

            // Sync semua intent dari Dialogflow ke database
            $dialogflowIds = [];
            foreach ($response as $dialogflowIntent) {
                $trainingPhrases = [];
                foreach ($dialogflowIntent->getTrainingPhrases() as $phrase) {
                    $parts = [];
                    foreach ($phrase->getParts() as $part) {
                        $parts[] = ['text' => $part->getText()];
                    }
                    $trainingPhrases[] = ['parts' => $parts];
                }

                $responses = [];
                foreach ($dialogflowIntent->getMessages() as $message) {
                    if ($message->getText()) {
                        $responses = [
                            'text' => [
                                'text' => iterator_to_array($message->getText()->getText())
                            ]
                        ];
                        break;
                    }
                }

                $events = iterator_to_array($dialogflowIntent->getEvents());
                $intentId = basename($dialogflowIntent->getName());
                $dialogflowIds[] = $intentId;

                Intent::updateOrCreate(
                    ['dialogflow_id' => $dialogflowIntent->getName()],
                    [
                        'display_name' => $dialogflowIntent->getDisplayName(),
                        'priority' => $dialogflowIntent->getPriority(),
                        'is_fallback' => $dialogflowIntent->getIsFallback(),
                        'training_phrases' => $trainingPhrases,
                        'events' => $events,
                        'responses' => $responses,
                        'webhook_enabled' => $dialogflowIntent->getWebhookState() == 1,
                        'action' => $dialogflowIntent->getAction(),
                        'synced' => true,
                        'last_synced_at' => now(),
                    ]
                );
            }

            // Hapus intent yang sudah tidak ada di Dialogflow
            $dialogflowFullPaths = [];
            foreach ($response as $dialogflowIntent) {
                $dialogflowFullPaths[] = $dialogflowIntent->getName();
            }
            
            Intent::whereNotNull('dialogflow_id')
                ->whereNotIn('dialogflow_id', $dialogflowFullPaths)
                ->delete();

            $intentsClient->close();
        } catch (\Exception $e) {
            Log::error('Auto-sync Dialogflow error: ' . $e->getMessage());
            // Lanjutkan tampilkan data dari database meskipun sync gagal
        }

        $intents = Intent::orderBy('display_name')->paginate(20);
        
        // Add solved questions count to each intent
        foreach ($intents as $intent) {
            $intent->solved_questions_count = UnansweredQuestion::where('solved_by_intent_id', $intent->id)
                ->where('is_solved', true)
                ->count();
        }
        
        // Get unanswered questions - unsolved ones first
        $unsolvedQuestions = UnansweredQuestion::with('chatUser')
            ->where('is_solved', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get specific question if question_id is provided
        $highlightedQuestion = null;
        if (request()->has('question_id')) {
            $highlightedQuestion = UnansweredQuestion::find(request('question_id'));
        }
        
        return view('intents.index', compact('intents', 'unsolvedQuestions', 'highlightedQuestion'));
    }

    public function create()
    {
        return view('intents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255|unique:intents,display_name',
            'description' => 'nullable|string',
            'priority' => 'nullable|integer',
            'training_phrases' => 'nullable|array',
            'training_phrases.*' => 'nullable|string',
            'events' => 'nullable|array',
            'events.*' => 'nullable|string',
            'responses' => 'required|array|min:1',
            'responses.*' => 'required|string',
        ]);

        // Validate: must have either training_phrases OR events
        if (empty(array_filter($validated['training_phrases'] ?? [])) && empty(array_filter($validated['events'] ?? []))) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Intent harus memiliki minimal 1 training phrase ATAU 1 event.');
        }

        try {
            // Format training phrases
            $trainingPhrases = [];
            if (!empty($validated['training_phrases'])) {
                $trainingPhrases = array_map(function($phrase) {
                    return ['parts' => [['text' => $phrase]]];
                }, array_filter($validated['training_phrases']));
            }

            // Format responses
            $responses = [
                'text' => [
                    'text' => array_filter($validated['responses'])
                ]
            ];

            // Create in database
            $intent = Intent::create([
                'display_name' => $validated['display_name'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'] ?? 500000,
                'training_phrases' => $trainingPhrases,
                'events' => array_filter($validated['events'] ?? []),
                'responses' => $responses,
                'synced' => false,
            ]);

            // Sync to Dialogflow
            $this->syncToDialogflow($intent);

            return redirect()->route('intents.index')
                ->with('success', 'Intent berhasil dibuat dan disinkronkan ke Dialogflow!');
        } catch (\Exception $e) {
            Log::error('Intent store error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat intent: ' . $e->getMessage());
        }
    }

    public function edit(Intent $intent)
    {
        // Get solved questions related to this intent
        $solvedQuestions = UnansweredQuestion::with('chatUser')
            ->where('solved_by_intent_id', $intent->id)
            ->orderBy('solved_at', 'desc')
            ->get();
            
        return view('intents.edit', compact('intent', 'solvedQuestions'));
    }

    public function update(Request $request, Intent $intent)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255|unique:intents,display_name,' . $intent->id,
            'description' => 'nullable|string',
            'priority' => 'nullable|integer',
            'training_phrases' => 'nullable|array',
            'training_phrases.*' => 'nullable|string',
            'events' => 'nullable|array',
            'events.*' => 'nullable|string',
            'responses' => 'required|array|min:1',
            'responses.*' => 'required|string',
        ]);

        // Validate: must have either training_phrases OR events
        if (empty(array_filter($validated['training_phrases'] ?? [])) && empty(array_filter($validated['events'] ?? []))) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Intent harus memiliki minimal 1 training phrase ATAU 1 event.');
        }

        try {
            // Get old training phrases for comparison
            $oldPhrases = [];
            foreach ($intent->training_phrases ?? [] as $phrase) {
                if (is_array($phrase) && isset($phrase['parts'][0]['text'])) {
                    $oldPhrases[] = $phrase['parts'][0]['text'];
                }
            }
            
            // Format training phrases
            $trainingPhrases = [];
            $newPhrases = [];
            if (!empty($validated['training_phrases'])) {
                foreach (array_filter($validated['training_phrases']) as $phrase) {
                    $trainingPhrases[] = ['parts' => [['text' => $phrase]]];
                    $newPhrases[] = $phrase;
                }
            }
            
            // Find removed phrases
            $removedPhrases = array_diff($oldPhrases, $newPhrases);
            
            // Check if any removed phrase belongs to a solved question
            if (!empty($removedPhrases)) {
                foreach ($removedPhrases as $removedPhrase) {
                    $solvedQuestion = UnansweredQuestion::where('solved_by_intent_id', $intent->id)
                        ->where('is_solved', true)
                        ->where('question', $removedPhrase)
                        ->first();
                    
                    if ($solvedQuestion) {
                        // Mark as unsolved again
                        $solvedQuestion->update([
                            'is_solved' => false,
                            'solved_by_intent_id' => null,
                            'solved_at' => null
                        ]);
                    }
                }
            }

            // Format responses
            $responses = [
                'text' => [
                    'text' => array_filter($validated['responses'])
                ]
            ];

            // Update database
            $intent->update([
                'display_name' => $validated['display_name'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'] ?? 500000,
                'training_phrases' => $trainingPhrases,
                'events' => array_filter($validated['events'] ?? []),
                'responses' => $responses,
                'synced' => false,
            ]);

            // Sync to Dialogflow
            $this->syncToDialogflow($intent);

            return redirect()->route('intents.index')
                ->with('success', 'Intent berhasil diupdate dan disinkronkan ke Dialogflow!');
        } catch (\Exception $e) {
            Log::error('Intent update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate intent: ' . $e->getMessage());
        }
    }

    public function destroy(Intent $intent)
    {
        try {
            // Delete from Dialogflow first
            if ($intent->dialogflow_id) {
                $keyPath = storage_path('app/dialogflow/notarybot.json');
                $intentsClient = new IntentsClient([
                    'credentials' => $keyPath,
                ]);

                $deleteRequest = new DeleteIntentRequest();
                $deleteRequest->setName($intent->dialogflow_id);
                
                $intentsClient->deleteIntent($deleteRequest);
                $intentsClient->close();
            }

            // Delete from database
            $intent->delete();

            return redirect()->route('intents.index')
                ->with('success', 'Intent berhasil dihapus dari database dan Dialogflow!');
        } catch (\Exception $e) {
            Log::error('Intent delete error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus intent: ' . $e->getMessage());
        }
    }

    public function sync(Intent $intent)
    {
        try {
            $this->syncToDialogflow($intent);
            return redirect()->back()
                ->with('success', 'Intent berhasil disinkronkan ke Dialogflow!');
        } catch (\Exception $e) {
            Log::error('Intent sync error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }

    public function import()
    {
        try {
            $keyPath = storage_path('app/dialogflow/notarybot.json');
            $intentsClient = new IntentsClient([
                'credentials' => $keyPath,
            ]);

            $projectId = env('DIALOGFLOW_PROJECT_ID');
            $parent = $intentsClient->projectAgentName($projectId);

            $listRequest = new ListIntentsRequest();
            $listRequest->setParent($parent);
            $listRequest->setIntentView(IntentView::INTENT_VIEW_FULL);

            $response = $intentsClient->listIntents($listRequest);

            $imported = 0;
            foreach ($response as $dialogflowIntent) {
                $trainingPhrases = [];
                foreach ($dialogflowIntent->getTrainingPhrases() as $phrase) {
                    $parts = [];
                    foreach ($phrase->getParts() as $part) {
                        $parts[] = ['text' => $part->getText()];
                    }
                    $trainingPhrases[] = ['parts' => $parts];
                }

                $responses = [];
                foreach ($dialogflowIntent->getMessages() as $message) {
                    if ($message->getText()) {
                        $responses = [
                            'text' => [
                                'text' => iterator_to_array($message->getText()->getText())
                            ]
                        ];
                        break;
                    }
                }

                $events = iterator_to_array($dialogflowIntent->getEvents());

                Intent::updateOrCreate(
                    ['dialogflow_id' => $dialogflowIntent->getName()],
                    [
                        'display_name' => $dialogflowIntent->getDisplayName(),
                        'priority' => $dialogflowIntent->getPriority(),
                        'is_fallback' => $dialogflowIntent->getIsFallback(),
                        'training_phrases' => $trainingPhrases,
                        'events' => $events,
                        'responses' => $responses,
                        'webhook_enabled' => $dialogflowIntent->getWebhookState() == 1,
                        'action' => $dialogflowIntent->getAction(),
                        'synced' => true,
                        'last_synced_at' => now(),
                    ]
                );
                $imported++;
            }

            $intentsClient->close();

            return redirect()->back()
                ->with('success', "Berhasil mengimport {$imported} intents dari Dialogflow!");
        } catch (\Exception $e) {
            Log::error('Intent import error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    protected function syncToDialogflow(Intent $intent)
    {
        $keyPath = storage_path('app/dialogflow/notarybot.json');
        $intentsClient = new IntentsClient([
            'credentials' => $keyPath,
        ]);

        $projectId = env('DIALOGFLOW_PROJECT_ID');
        $parent = $intentsClient->projectAgentName($projectId);

        // Create Dialogflow Intent object
        $dialogflowIntent = new DialogflowIntent();
        $dialogflowIntent->setDisplayName($intent->display_name);
        $dialogflowIntent->setPriority($intent->priority);

        // Add training phrases
        if (!empty($intent->training_phrases)) {
            $trainingPhrases = [];
            foreach ($intent->training_phrases as $phrase) {
                $parts = [];
                foreach ($phrase['parts'] as $partData) {
                    $part = new Part();
                    $part->setText($partData['text']);
                    $parts[] = $part;
                }
                $trainingPhrase = new TrainingPhrase();
                $trainingPhrase->setParts($parts);
                $trainingPhrases[] = $trainingPhrase;
            }
            $dialogflowIntent->setTrainingPhrases($trainingPhrases);
        }

        // Add events
        if (!empty($intent->events)) {
            $dialogflowIntent->setEvents($intent->events);
        }

        // Add responses
        if (!empty($intent->responses['text']['text'])) {
            $text = new Text();
            $text->setText($intent->responses['text']['text']);
            
            $message = new Message();
            $message->setText($text);
            
            $dialogflowIntent->setMessages([$message]);
        }

        try {
            if ($intent->dialogflow_id) {
                // Update existing intent
                $dialogflowIntent->setName($intent->dialogflow_id);
                
                $updateRequest = new UpdateIntentRequest();
                $updateRequest->setIntent($dialogflowIntent);
                $updateRequest->setLanguageCode('id');
                
                $response = $intentsClient->updateIntent($updateRequest);
            } else {
                // Create new intent
                $createRequest = new CreateIntentRequest();
                $createRequest->setParent($parent);
                $createRequest->setIntent($dialogflowIntent);
                $createRequest->setLanguageCode('id');
                
                $response = $intentsClient->createIntent($createRequest);
                
                // Save Dialogflow ID
                $intent->dialogflow_id = $response->getName();
            }

            $intent->synced = true;
            $intent->last_synced_at = now();
            $intent->save();

            $intentsClient->close();
        } catch (\Exception $e) {
            $intentsClient->close();
            throw $e;
        }
    }
    
    public function markQuestionSolved(Request $request, Intent $intent)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:unanswered_questions,id'
        ]);
        
        $question = UnansweredQuestion::find($validated['question_id']);
        
        // Add question as training phrase to the intent
        $trainingPhrases = $intent->training_phrases ?? [];
        
        // Check if the question is not already in training phrases
        $questionAdded = false;
        $questionText = $question->question;
        
        // Check if question already exists (check in the parts array)
        $alreadyExists = false;
        foreach ($trainingPhrases as $phrase) {
            if (is_array($phrase) && isset($phrase['parts'][0]['text'])) {
                if ($phrase['parts'][0]['text'] === $questionText) {
                    $alreadyExists = true;
                    break;
                }
            }
        }
        
        if (!$alreadyExists) {
            // Add in correct Dialogflow format
            $trainingPhrases[] = [
                'parts' => [
                    ['text' => $questionText]
                ]
            ];
            $intent->training_phrases = $trainingPhrases;
            $intent->save();
            $questionAdded = true;
            
            // Auto-sync to Dialogflow
            try {
                $this->syncToDialogflow($intent);
                $syncMessage = ' and synced to Dialogflow successfully.';
            } catch (\Exception $e) {
                Log::error('Auto-sync after solving question failed: ' . $e->getMessage());
                $syncMessage = ' but sync to Dialogflow failed. Please sync manually.';
            }
        } else {
            $syncMessage = '';
        }
        
        // Mark question as solved
        $question->update([
            'is_solved' => true,
            'solved_by_intent_id' => $intent->id,
            'solved_at' => now()
        ]);
        
        $message = 'Question marked as solved! ';
        if ($questionAdded) {
            $message .= 'Training phrase "' . $question->question . '" has been added to intent: ' . $intent->display_name . $syncMessage;
        }
        
        return redirect()->route('intents.index')
            ->with('success', $message);
    }
}

