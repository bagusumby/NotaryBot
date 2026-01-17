@extends('layouts.dashboard')

@section('title', 'Dashboard - Notary Services')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl text-gray-900 mb-2">Dashboard</h1>
            <p class="text-gray-600">Welcome back! Here's your bot analytics overview.</p>
        </div>

        <!-- Top Stats - 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Bot Users -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-users w-6 h-6 text-blue-600"></i>
                    </div>
                    <div class="flex items-center gap-1 {{ $userGrowth >= 0 ? 'text-blue-600' : 'text-red-600' }} text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4 {{ $userGrowth < 0 ? 'rotate-180' : '' }}"></i>
                        <span>{{ $userGrowth >= 0 ? '+' : '' }}{{ number_format($userGrowth, 1) }}%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">{{ number_format($totalUsers) }}</p>
                <p class="text-gray-600">Total Bot Users</p>
                <p class="text-sm text-gray-500 mt-2">+{{ number_format($usersThisMonth) }} this month</p>
            </div>

            <!-- Positive Reviews -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-thumbs-up w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex items-center gap-1 {{ $positiveGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4 {{ $positiveGrowth < 0 ? 'rotate-180' : '' }}"></i>
                        <span>{{ $positiveGrowth >= 0 ? '+' : '' }}{{ number_format($positiveGrowth, 1) }}%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">{{ number_format($positiveReviews) }}</p>
                <p class="text-gray-600">Positive Reviews</p>
                <p class="text-sm text-gray-500 mt-2">{{ number_format($satisfactionRate, 1) }}% satisfaction rate</p>
            </div>

            <!-- Negative Reviews -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-red-50 p-3 rounded-lg">
                        <i class="fas fa-thumbs-down w-6 h-6 text-red-600"></i>
                    </div>
                    <div class="flex items-center gap-1 {{ $negativeGrowth <= 0 ? 'text-green-600' : 'text-red-600' }} text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4 {{ $negativeGrowth <= 0 ? 'rotate-180' : '' }}"></i>
                        <span>{{ $negativeGrowth <= 0 ? '' : '+' }}{{ number_format($negativeGrowth, 1) }}%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">{{ number_format($negativeReviews) }}</p>
                <p class="text-gray-600">Negative Reviews</p>
                <p class="text-sm text-gray-500 mt-2">{{ number_format($improvementRate, 1) }}% needs improvement</p>
            </div>
        </div>

        <!-- Secondary Stats - 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Conversations -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <i class="fas fa-comment w-6 h-6 text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($totalConversations) }}</p>
                        <p class="text-gray-600">Total Conversations</p>
                        <p class="text-sm text-gray-500 mt-1">Average {{ number_format($avgConversationsPerDay) }}/day</p>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="bg-orange-50 p-3 rounded-lg">
                        <i class="fas fa-chart-bar w-6 h-6 text-orange-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($successRate, 1) }}%</p>
                        <p class="text-gray-600">Success Rate</p>
                        <p class="text-sm text-gray-500 mt-1">Bot resolved queries</p>
                    </div>
                </div>
            </div>

            <!-- Total Intents -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="bg-cyan-50 p-3 rounded-lg">
                        <i class="fas fa-brain w-6 h-6 text-cyan-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($totalIntents) }}</p>
                        <p class="text-gray-600">Total Intents</p>
                        <p class="text-sm text-gray-500 mt-1">Bot training data</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unanswered Questions Stats - 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Unanswered Questions -->
            <a href="{{ route('unanswered-questions') }}" class="bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-xl shadow-sm border border-yellow-200 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-question-circle w-6 h-6 text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($totalUnansweredQuestions) }}</p>
                        <p class="text-gray-600 font-medium">Total Questions</p>
                        <p class="text-sm text-gray-500 mt-1">Click to view all</p>
                    </div>
                </div>
            </a>

            <!-- Unsolved Questions -->
            <a href="{{ route('unanswered-questions') }}" class="bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-xl shadow-sm border border-red-200 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-exclamation-triangle w-6 h-6 text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($unsolvedQuestions) }}</p>
                        <p class="text-gray-600 font-medium">Unsolved Questions</p>
                        <p class="text-sm text-gray-500 mt-1">Needs attention</p>
                    </div>
                </div>
            </a>

            <!-- Solved Questions -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl shadow-sm border border-green-200">
                <div class="flex items-start gap-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check-circle w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($solvedQuestions) }}</p>
                        <p class="text-gray-600 font-medium">Solved Questions</p>
                        <p class="text-sm text-gray-500 mt-1">+{{ number_format($solvedQuestionsThisMonth) }} this month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('reports') }}"
                    class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-file-alt w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-900">View Reports</p>
                        <p class="text-sm text-gray-500">Check detailed analytics</p>
                    </div>
                </a>

                <a href="{{ route('bot-training') }}"
                    class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-robot w-6 h-6 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-900">Train Bot</p>
                        <p class="text-sm text-gray-500">Add new responses</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl text-gray-900 mb-4">Recent Users</h2>
                @if($recentUsers->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-gray-900 font-medium">{{ $user->name ?? 'Anonymous' }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email ?? 'No email' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No recent users</p>
                @endif
            </div>

            <!-- Recent Reviews -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl text-gray-900 mb-4">Recent Reviews</h2>
                @if($recentReviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentReviews as $review)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    @if($review->rating === 'positive')
                                        <i class="fas fa-thumbs-up text-green-600"></i>
                                    @else
                                        <i class="fas fa-thumbs-down text-red-600"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-900 font-medium text-sm">{{ $review->chatUser->name ?? 'Anonymous' }}</p>
                                    @if($review->comment)
                                        <p class="text-sm text-gray-600 truncate">{{ $review->comment }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No recent reviews</p>
                @endif
            </div>
        </div>
    </div>
@endsection
