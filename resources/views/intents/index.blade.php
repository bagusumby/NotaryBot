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
