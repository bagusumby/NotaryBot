<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnansweredQuestion;

class UnansweredQuestionController extends Controller
{
    public function index()
    {
        // Get all unanswered questions with user data, ordered by most recent
        $questions = UnansweredQuestion::with(['chatUser', 'solvedByIntent'])
            ->orderBy('is_solved', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalQuestions = $questions->count();
        $unsolvedQuestions = $questions->where('is_solved', false)->count();
        $solvedQuestions = $questions->where('is_solved', true)->count();
        
        return view('unanswered-questions.index', compact('questions', 'totalQuestions', 'unsolvedQuestions', 'solvedQuestions'));
    }
}
