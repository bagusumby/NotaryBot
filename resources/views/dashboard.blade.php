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
                    <div class="flex items-center gap-1 text-blue-600 text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4"></i>
                        <span>+12.5%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">2,847</p>
                <p class="text-gray-600">Total Bot Users</p>
                <p class="text-sm text-gray-500 mt-2">+324 this month</p>
            </div>

            <!-- Positive Reviews -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-thumbs-up w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex items-center gap-1 text-green-600 text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4"></i>
                        <span>+8.3%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">2,156</p>
                <p class="text-gray-600">Positive Reviews</p>
                <p class="text-sm text-gray-500 mt-2">75.7% satisfaction rate</p>
            </div>

            <!-- Negative Reviews -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-red-50 p-3 rounded-lg">
                        <i class="fas fa-thumbs-down w-6 h-6 text-red-600"></i>
                    </div>
                    <div class="flex items-center gap-1 text-red-600 text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4 rotate-180"></i>
                        <span>-3.2%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">691</p>
                <p class="text-gray-600">Negative Reviews</p>
                <p class="text-sm text-gray-500 mt-2">24.3% needs improvement</p>
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
                        <p class="text-2xl text-gray-900 mb-1">8,542</p>
                        <p class="text-gray-600">Total Conversations</p>
                        <p class="text-sm text-gray-500 mt-1">Average 286/day</p>
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
                        <p class="text-2xl text-gray-900 mb-1">94.2%</p>
                        <p class="text-gray-600">Success Rate</p>
                        <p class="text-sm text-gray-500 mt-1">Bot resolved queries</p>
                    </div>
                </div>
            </div>

            <!-- Avg Response Time -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="bg-cyan-50 p-3 rounded-lg">
                        <i class="fas fa-clock w-6 h-6 text-cyan-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">1.2s</p>
                        <p class="text-gray-600">Avg Response Time</p>
                        <p class="text-sm text-gray-500 mt-1">-0.3s from last month</p>
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
    </div>
@endsection
