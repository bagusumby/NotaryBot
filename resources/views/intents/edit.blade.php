@extends('layouts.dashboard')

@section('title', 'Edit Intent')

@section('content')
    <div class="p-6">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('intents.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Intent</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-200">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-edit text-2xl text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Intent</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $intent->display_name }}</p>
                </div>
            </div>

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                    <ul class="list-disc list-inside text-red-800 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('intents.update', $intent) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Intent -->
                <div>
                    <label for="display_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-gray-400 mr-1"></i>
                        Nama Intent <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="display_name" name="display_name"
                        value="{{ old('display_name', $intent->display_name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Contoh: greeting.hello">
                    <p class="text-xs text-gray-500 mt-1.5">Nama unik untuk intent ini (gunakan format: kategori.nama)</p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-gray-400 mr-1"></i>
                        Deskripsi
                    </label>
                    <textarea id="description" name="description" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Deskripsi singkat tentang intent ini">{{ old('description', $intent->description) }}</textarea>
                </div>

                <!-- Training Phrases -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comments text-blue-600 mr-1"></i>
                        Training Phrases (Pertanyaan User) <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Masukkan berbagai variasi pertanyaan yang mungkin ditanyakan user
                    </p>
                    <div id="training-phrases-container" class="space-y-3">
                        @php
                            $trainingPhrases = old('training_phrases');
                            if (!$trainingPhrases) {
                                $trainingPhrases = array_map(function ($phrase) {
                                    return $phrase['parts'][0]['text'] ?? '';
                                }, $intent->training_phrases ?? []);
                            }
                        @endphp

                        @forelse($trainingPhrases as $phrase)
                            <div class="flex gap-2 items-start phrase-row">
                                <span
                                    class="bg-blue-600 text-white w-8 h-10 flex items-center justify-center rounded-lg text-sm font-semibold flex-shrink-0">{{ $loop->iteration }}</span>
                                <input type="text" name="training_phrases[]" value="{{ $phrase }}"
                                    class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Halo, Selamat pagi, Hai bot">
                                <button type="button"
                                    class="remove-phrase px-3 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex-shrink-0">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @empty
                            <div class="flex gap-2 items-start phrase-row">
                                <span
                                    class="bg-blue-600 text-white w-8 h-10 flex items-center justify-center rounded-lg text-sm font-semibold flex-shrink-0">1</span>
                                <input type="text" name="training_phrases[]"
                                    class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Halo, Selamat pagi, Hai bot">
                                <button type="button"
                                    class="remove-phrase px-3 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex-shrink-0">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" id="add-phrase"
                        class="mt-4 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Phrase</span>
                    </button>
                </div>

                <!-- Responses -->
                <div class="bg-purple-50 rounded-lg p-6 border border-purple-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-reply text-purple-600 mr-1"></i>
                        Responses (Jawaban Bot) <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle text-purple-500"></i>
                        Bot akan memilih salah satu jawaban secara random saat merespons
                    </p>
                    <div id="responses-container" class="space-y-3">
                        @php
                            $responses = old('responses', $intent->responses['text']['text'] ?? []);
                        @endphp

                        @foreach ($responses as $response)
                            <div class="flex gap-2 items-start response-row">
                                <span
                                    class="bg-purple-600 text-white w-8 h-10 flex items-center justify-center rounded-lg text-sm font-semibold flex-shrink-0">{{ $loop->iteration }}</span>
                                <textarea name="responses[]" rows="2" required
                                    class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Contoh: Halo! Ada yang bisa saya bantu?">{{ $response }}</textarea>
                                <button type="button"
                                    class="remove-response px-3 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex-shrink-0">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-response"
                        class="mt-4 px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Response</span>
                    </button>
                </div>

                <!-- Sync Info -->
                @if ($intent->synced)
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                        <div class="flex items-center gap-2 text-green-800">
                            <i class="fas fa-check-circle"></i>
                            <span class="font-medium">Intent ini sudah disinkronisasi dengan Dialogflow</span>
                        </div>
                        <p class="text-sm text-green-700 mt-1">Terakhir disinkronisasi:
                            {{ $intent->last_synced_at->diffForHumans() }}</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update Intent</span>
                    </button>
                    <a href="{{ route('intents.index') }}"
                        class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-semibold transition-colors flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateNumbers() {
                document.querySelectorAll('.phrase-row').forEach((row, index) => {
                    row.querySelector('span').textContent = index + 1;
                });
                document.querySelectorAll('.response-row').forEach((row, index) => {
                    row.querySelector('span').textContent = index + 1;
                });
            }

            document.getElementById('add-phrase').addEventListener('click', function() {
                const container = document.getElementById('training-phrases-container');
                const count = container.children.length + 1;
                const newPhrase = `
        <div class="flex gap-2 items-start phrase-row">
            <span class="bg-blue-600 text-white w-8 h-10 flex items-center justify-center rounded-lg text-sm font-semibold flex-shrink-0">${count}</span>
            <input type="text" name="training_phrases[]"
                   class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: Halo, Selamat pagi, Hai bot">
            <button type="button" class="remove-phrase px-3 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
                container.insertAdjacentHTML('beforeend', newPhrase);
            });

            document.getElementById('add-response').addEventListener('click', function() {
                const container = document.getElementById('responses-container');
                const count = container.children.length + 1;
                const newResponse = `
        <div class="flex gap-2 items-start response-row">
            <span class="bg-purple-600 text-white w-8 h-10 flex items-center justify-center rounded-lg text-sm font-semibold flex-shrink-0">${count}</span>
            <textarea name="responses[]" rows="2" required
                      class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                      placeholder="Contoh: Halo! Ada yang bisa saya bantu?"></textarea>
            <button type="button" class="remove-response px-3 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
                container.insertAdjacentHTML('beforeend', newResponse);
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-phrase') || e.target.closest('.remove-phrase')) {
                    const button = e.target.classList.contains('remove-phrase') ? e.target : e.target.closest(
                        '.remove-phrase');
                    const container = document.getElementById('training-phrases-container');
                    if (container.children.length > 1) {
                        button.closest('.phrase-row').remove();
                        updateNumbers();
                    }
                }

                if (e.target.classList.contains('remove-response') || e.target.closest('.remove-response')) {
                    const button = e.target.classList.contains('remove-response') ? e.target : e.target.closest(
                        '.remove-response');
                    const container = document.getElementById('responses-container');
                    if (container.children.length > 1) {
                        button.closest('.response-row').remove();
                        updateNumbers();
                    }
                }
            });
        </script>
    @endpush
@endsection
