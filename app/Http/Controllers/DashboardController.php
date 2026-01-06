<?php

namespace App\Http\Controllers;

use App\Models\ChatUser;
use App\Models\Review;
use App\Models\Intent;
use App\Models\UnansweredQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total bot users
        $totalUsers = ChatUser::count();
        $usersThisMonth = ChatUser::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $usersLastMonth = ChatUser::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $userGrowth = $usersLastMonth > 0 ? (($usersThisMonth - $usersLastMonth) / $usersLastMonth * 100) : 0;

        // Reviews
        $totalReviews = Review::count();
        $positiveReviews = Review::where('rating', 'positive')->count();
        $negativeReviews = Review::where('rating', 'negative')->count();
        
        $positiveReviewsLastMonth = Review::where('rating', 'positive')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $positiveReviewsThisMonth = Review::where('rating', 'positive')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $positiveGrowth = $positiveReviewsLastMonth > 0 ? 
            (($positiveReviewsThisMonth - $positiveReviewsLastMonth) / $positiveReviewsLastMonth * 100) : 0;

        $negativeReviewsLastMonth = Review::where('rating', 'negative')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $negativeReviewsThisMonth = Review::where('rating', 'negative')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $negativeGrowth = $negativeReviewsLastMonth > 0 ? 
            (($negativeReviewsThisMonth - $negativeReviewsLastMonth) / $negativeReviewsLastMonth * 100) : 0;

        $satisfactionRate = $totalReviews > 0 ? ($positiveReviews / $totalReviews * 100) : 0;
        $improvementRate = $totalReviews > 0 ? ($negativeReviews / $totalReviews * 100) : 0;

        // Total conversations (using reviews as proxy for conversations)
        $totalConversations = $totalReviews;
        $avgConversationsPerDay = $totalReviews > 0 ? 
            round($totalReviews / max(1, now()->diffInDays(Review::min('created_at') ?: now()))) : 0;

        // Success rate (conversations with positive reviews vs total)
        $successRate = $totalReviews > 0 ? ($positiveReviews / $totalReviews * 100) : 0;

        // Unanswered questions
        $unansweredQuestions = UnansweredQuestion::count();

        // Total intents
        $totalIntents = Intent::count();

        // Recent activity - Last 7 days
        $recentUsers = ChatUser::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent reviews - Last 5
        $recentReviews = Review::with('chatUser')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalUsers',
            'usersThisMonth',
            'userGrowth',
            'positiveReviews',
            'negativeReviews',
            'positiveGrowth',
            'negativeGrowth',
            'satisfactionRate',
            'improvementRate',
            'totalConversations',
            'avgConversationsPerDay',
            'successRate',
            'unansweredQuestions',
            'totalIntents',
            'recentUsers',
            'recentReviews'
        ));
    }
}

