@extends('layouts.dashboard')

@section('title', 'Unanswered Questions')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-question-circle w-6 h-6 text-gray-700"></i>
                <h1 class="text-2xl font-bold text-gray-800">Unanswered Questions</h1>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-50 rounded-lg">
                        <i class="fas fa-question-circle text-orange-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Questions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalQuestions }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Unsolved</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $unsolvedQuestions }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Solved</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $solvedQuestions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unanswered Questions Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User Info</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Question</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bot Response</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($questions as $question)
                            <tr class="hover:bg-gray-50 transition-colors {{ $question->is_solved ? 'bg-green-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    @if($question->is_solved)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                <i class="fas fa-check-circle"></i> Solved
                                            </span>
                                            @if($question->solvedByIntent)
                                                <span class="text-xs text-gray-500" title="{{ $question->solvedByIntent->display_name }}">
                                                    via {{ Str::limit($question->solvedByIntent->display_name, 20) }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                            <i class="fas fa-exclamation-circle"></i> Unsolved
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($question->chatUser)
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $question->chatUser->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $question->chatUser->email }}</div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Guest User</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 max-w-md">{{ $question->question }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-500 max-w-xs truncate"
                                        title="{{ $question->bot_response }}">
                                        {{ $question->bot_response }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        {{ $question->created_at->format('d M Y') }}
                                        <div class="text-xs text-gray-400">{{ $question->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(!$question->is_solved)
                                        <a href="{{ route('intents.index', ['question_id' => $question->id]) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                            <i class="fas fa-robot"></i>
                                            Solve in Training
                                        </a>
                                    @else
                                        <div class="text-xs text-gray-500">
                                            Solved {{ $question->solved_at ? $question->solved_at->diffForHumans() : '' }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-check-circle text-4xl mb-3 text-green-400"></i>
                                        <p class="text-lg font-medium text-gray-600">Great! All questions answered</p>
                                        <p class="text-sm">No unanswered questions found</p>
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
