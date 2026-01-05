<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intent;
use App\Models\ChatUser;
use App\Models\Review;

class ReportsController extends Controller
{
    public function index()
    {
        // Get all intents with usage count > 0, ordered by usage
        $intents = Intent::where('usage_count', '>', 0)
            ->orderBy('usage_count', 'desc')
            ->get();
        
        // Calculate statistics
        $totalIntentUsage = Intent::sum('usage_count');
        $totalUsers = ChatUser::count();
        $totalReviews = Review::count();
        $positiveReviews = Review::where('rating', 'positive')->count();
        $negativeReviews = Review::where('rating', 'negative')->count();
        
        // Calculate percentages for each intent
        $intents = $intents->map(function($intent) use ($totalIntentUsage) {
            $intent->percentage = $totalIntentUsage > 0 
                ? round(($intent->usage_count / $totalIntentUsage) * 100, 2) 
                : 0;
            return $intent;
        });
        
        return view('reports', compact(
            'intents',
            'totalIntentUsage',
            'totalUsers',
            'totalReviews',
            'positiveReviews',
            'negativeReviews'
        ));
    }
}
