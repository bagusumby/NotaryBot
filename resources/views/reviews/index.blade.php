@extends('layouts.dashboard')

@section('title', 'Report Reviews')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-star w-6 h-6 text-gray-700"></i>
                <h1 class="text-2xl font-bold text-gray-800">Report Reviews</h1>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <i class="fas fa-comments text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Reviews</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $reviews->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <i class="fas fa-thumbs-up text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Positive Reviews</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $positiveCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-lg">
                        <i class="fas fa-thumbs-down text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Negative Reviews</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $negativeCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User Info</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Session ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rating</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Comment</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($reviews as $review)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $review->chatUser->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->chatUser->email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-600 font-mono">{{ Str::limit($review->session_id, 20) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($review->rating === 'positive')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            <i class="fas fa-thumbs-up"></i>
                                            Positive
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                            <i class="fas fa-thumbs-down"></i>
                                            Negative
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($review->comment)
                                        <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $review->comment }}">
                                            "{{ $review->comment }}"
                                        </p>
                                    @else
                                        <span class="text-sm text-gray-400 italic">No comment</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        {{ $review->created_at->format('d M Y') }}
                                        <div class="text-xs text-gray-400">{{ $review->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">No reviews yet</p>
                                        <p class="text-sm">User reviews will appear here</p>
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
