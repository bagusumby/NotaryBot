<?php

namespace App\Http\Controllers;

use App\Models\ChatUser;
use App\Models\Review;
use App\Models\Intent;
use App\Models\UnansweredQuestion;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Unanswered questions statistics
        $totalUnansweredQuestions = UnansweredQuestion::count();
        $unsolvedQuestions = UnansweredQuestion::where('is_solved', false)->count();
        $solvedQuestions = UnansweredQuestion::where('is_solved', true)->count();
        $solvedQuestionsThisMonth = UnansweredQuestion::where('is_solved', true)
            ->whereMonth('solved_at', now()->month)
            ->whereYear('solved_at', now()->year)
            ->count();

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

        // Chart Data: Appointments by Day of Week
        $appointmentsByDay = Appointment::select(
            DB::raw('DAYOFWEEK(booking_date) as day_number'),
            DB::raw('count(*) as total')
        )
            ->whereNotNull('booking_date')
            ->groupBy('day_number')
            ->orderBy('day_number')
            ->get()
            ->pluck('total', 'day_number')
            ->toArray();

        // Convert day numbers to names (1=Sunday, 2=Monday, etc.)
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $appointmentsByDayFormatted = [];
        for ($i = 1; $i <= 7; $i++) {
            $appointmentsByDayFormatted[$dayNames[$i - 1]] = $appointmentsByDay[$i] ?? 0;
        }

        // Chart Data: User Growth - Last 6 months
        $userGrowthChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $userGrowthChart[$month->format('M Y')] = ChatUser::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Chart Data: Reviews Trend - Last 6 months
        $reviewsTrendChart = [
            'positive' => [],
            'negative' => [],
            'months' => []
        ];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabel = $month->format('M Y');
            $reviewsTrendChart['months'][] = $monthLabel;
            $reviewsTrendChart['positive'][] = Review::where('rating', 'positive')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $reviewsTrendChart['negative'][] = Review::where('rating', 'negative')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Chart Data: Unanswered Questions Performance - Last 6 months
        $unansweredQuestionsChart = [
            'total' => [],
            'solved' => [],
            'unsolved' => [],
            'months' => []
        ];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabel = $month->format('M Y');
            $unansweredQuestionsChart['months'][] = $monthLabel;
            
            $totalQuestions = UnansweredQuestion::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $solvedInMonth = UnansweredQuestion::where('is_solved', true)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $unansweredQuestionsChart['total'][] = $totalQuestions;
            $unansweredQuestionsChart['solved'][] = $solvedInMonth;
            $unansweredQuestionsChart['unsolved'][] = $totalQuestions - $solvedInMonth;
        }

        // Chart Data: Appointment Status Distribution
        $appointmentStatusChart = Appointment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

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
            'totalUnansweredQuestions',
            'unsolvedQuestions',
            'solvedQuestions',
            'solvedQuestionsThisMonth',
            'totalIntents',
            'recentUsers',
            'recentReviews',
            'appointmentsByDayFormatted',
            'userGrowthChart',
            'reviewsTrendChart',
            'unansweredQuestionsChart',
            'appointmentStatusChart'
        ));
    }
}

