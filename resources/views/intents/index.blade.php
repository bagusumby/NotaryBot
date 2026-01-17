@extends('layouts.dashboard')

@section('title', 'Bot Training - Manage Intents')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-robot text-2xl text-gray-700"></i>
                <h1 class="text-2xl font-bold text-gray-800">Bot Training</h1>
            </div>
            <a href="{{ route('intents.create') }}"
                class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Intent Baru</span>
            </a>
        </div>

        <!-- Unsolved Questions Section -->
        @if($unsolvedQuestions->count() > 0)
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <i class="fas fa-lightbulb text-yellow-600"></i>
                        {{ $unsolvedQuestions->count() }} Unsolved Questions
                    </h3>
                    <p class="text-gray-600 mb-4">
                        These questions couldn't be answered by the bot. Create or link an intent to solve them.
                    </p>
                    
                    <div class="space-y-3">
                        @foreach($unsolvedQuestions as $question)
                        <div class="bg-white p-4 rounded-lg border {{ $highlightedQuestion && $highlightedQuestion->id == $question->id ? 'border-blue-400 shadow-lg' : 'border-gray-200' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium mb-1">{{ $question->question }}</p>
                                    <div class="flex items-center gap-3 text-sm text-gray-500">
                                        <span><i class="fas fa-user"></i> {{ $question->chatUser->name ?? 'Guest' }}</span>
                                        <span><i class="fas fa-clock"></i> {{ $question->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <button 
                                    onclick="document.getElementById('question-{{ $question->id }}').classList.toggle('hidden')"
                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    Link to Intent
                                </button>
                            </div>
                            
                            <!-- Link to Intent Form -->
                            <div id="question-{{ $question->id }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Select an intent that solves this question:</p>
                                <form action="{{ route('intents.mark-solved', '__INTENT_ID__') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                                    <select name="intent_id" required onchange="this.form.action = this.form.action.replace('__INTENT_ID__', this.value)" 
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="">Select an intent...</option>
                                        @foreach($intents as $intent)
                                            <option value="{{ $intent->id }}">{{ $intent->display_name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                                        <i class="fas fa-check"></i> Mark Solved
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <a href="{{ route('unanswered-questions') }}" 
                       class="inline-flex items-center gap-2 mt-4 text-blue-600 hover:text-blue-700 font-medium text-sm">
                        View all unanswered questions <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if ($intents->count() == 0)
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Belum ada intent di Dialogflow</h3>
                        <p class="text-blue-800 text-sm">
                            Klik <strong>"Tambah Intent Baru"</strong> untuk membuat intent pertama. Setiap intent bisa
                            memiliki beberapa training phrases (pertanyaan) dan responses (jawaban).
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Intents Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Intent</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Training Phrases</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Responses</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($intents as $intent)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $intent->display_name }}</div>
                                    @if ($intent->description)
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($intent->description, 50) }}
                                        </div>
                                    @endif
                                    @if ($intent->is_fallback)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                            Fallback
                                        </span>
                                    @endif
                                    @if (isset($intent->solved_questions_count) && $intent->solved_questions_count > 0)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1"
                                            title="{{ $intent->solved_questions_count }} solved question(s)">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $intent->solved_questions_count }} Solved
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if (count($intent->training_phrases ?? []) > 0)
                                        <div class="text-sm text-gray-900">
                                            {{ count($intent->training_phrases) }} phrases
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @foreach (array_slice($intent->training_phrases, 0, 1) as $phrase)
                                                "{{ Str::limit($phrase['parts'][0]['text'] ?? '', 40) }}"
                                            @endforeach
                                            @if (count($intent->training_phrases) > 1)
                                                <div class="mt-0.5">+{{ count($intent->training_phrases) - 1 }} more</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No phrases</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ count($intent->responses['text']['text'] ?? []) }} responses
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($intent->synced)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            Synced
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $intent->last_synced_at?->diffForHumans() }}
                                        </div>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            Not Synced
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('intents.edit', $intent) }}"
                                            class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('intents.sync', $intent) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-800 transition-colors"
                                                title="Sync">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('intents.destroy', $intent) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus intent ini dari database dan Dialogflow?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-robot text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">Belum Ada Intent</p>
                                        <p class="text-sm mt-1">Klik "Tambah Intent Baru" untuk membuat intent pertama</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($intents->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $intents->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
