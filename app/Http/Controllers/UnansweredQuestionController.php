<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnansweredQuestion;

class UnansweredQuestionController extends Controller
{
    public function index()
    {
        // Get all unanswered questions with user data, ordered by most recent
        $questions = UnansweredQuestion::with('chatUser')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalQuestions = $questions->count();
        
        return view('unanswered-questions.index', compact('questions', 'totalQuestions'));
    }
}
