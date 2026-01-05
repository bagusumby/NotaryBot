<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        // Fetch all reviews with user data, ordered by most recent
        $reviews = Review::with('chatUser')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Count positive and negative reviews
        $positiveCount = $reviews->where('rating', 'positive')->count();
        $negativeCount = $reviews->where('rating', 'negative')->count();
        
        return view('reviews.index', compact('reviews', 'positiveCount', 'negativeCount'));
    }
}
