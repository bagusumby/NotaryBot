@extends('layouts.dashboard')

@section('title', 'Report Intent')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-chart-bar w-6 h-6 text-gray-700"></i>
                <h1 class="text-2xl font-bold text-gray-800">Report Intent</h1>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Total Intent Usage</p>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-comment w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($totalIntentUsage) }}</p>
                <p class="text-sm text-gray-500 mt-1">Questions answered</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Total Users</p>
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-users w-5 h-5 text-purple-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                <p class="text-sm text-gray-500 mt-1">Registered users</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Positive Reviews</p>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-thumbs-up w-5 h-5 text-green-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($positiveReviews) }}</p>
                <p class="text-sm text-gray-500 mt-1">of {{ number_format($totalReviews) }} total</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Negative Reviews</p>
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="fas fa-thumbs-down w-5 h-5 text-red-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($negativeReviews) }}</p>
                <p class="text-sm text-gray-500 mt-1">Needs improvement</p>
            </div>
        </div>

        <!-- Intent Usage Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Intent Usage Statistics</h2>
                <p class="text-sm text-gray-600 mt-1">Intent yang paling sering ditanyakan oleh pengguna</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-16">#</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Intent Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Usage Count</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($intents as $index => $intent)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-700">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $intent->display_name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-600">{{ $intent->description ?? 'No description' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fas fa-comments text-xs"></i>
                                        {{ number_format($intent->usage_count) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[200px]">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                style="width: {{ $intent->percentage }}%"></div>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-700 min-w-[50px]">{{ $intent->percentage }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">No intent usage data yet</p>
                                        <p class="text-sm">Intent usage statistics will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
