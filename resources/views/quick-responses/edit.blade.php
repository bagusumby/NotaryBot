@extends('layouts.dashboard')

@section('title', 'Edit Quick Response - Notary Services')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('quick-responses.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Quick Response Management
            </a>
            <h1 class="text-3xl text-gray-900 mb-2 mt-4">Edit Quick Response</h1>
            <p class="text-gray-600">Update quick response yang akan muncul sebagai chips di chatbot</p>
        </div>

        <!-- Saran dari Intent yang Sering Ditanyakan -->
        @if($popularIntents->count() > 0)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="bg-blue-600 p-3 rounded-lg">
                    <i class="fas fa-lightbulb text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg text-gray-900 mb-2">💡 Saran Pertanyaan yang Sering Ditanyakan</h3>
                    <p class="text-gray-600 mb-4">Klik untuk mengganti dengan intent populer:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($popularIntents as $intent)
                        <button onclick="fillForm('{{ addslashes($intent->display_name) }}')" 
                                class="bg-white p-4 rounded-lg border border-blue-200 flex items-center justify-between hover:shadow-md hover:border-blue-400 transition text-left">
                            <div class="flex-1">
                                <p class="text-gray-900 font-medium">{{ $intent->display_name }}</p>
                                <p class="text-sm text-gray-500">Digunakan {{ $intent->usage_count }}x</p>
                            </div>
                            <i class="fas fa-arrow-right text-blue-600"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('quick-responses.update', $quickResponse) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="title" class="block text-gray-700 font-medium mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-500 mb-2">Text yang akan ditampilkan pada button chip</p>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $quickResponse->title) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Contoh: Cara Legalisir Dokumen"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="value" class="block text-gray-700 font-medium mb-2">
                        Value <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-500 mb-2">Text yang akan dikirim ke bot saat chip diklik</p>
                    <input type="text" 
                           name="value" 
                           id="value" 
                           value="{{ old('value', $quickResponse->value) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('value') border-red-500 @enderror"
                           placeholder="Contoh: Bagaimana cara legalisir dokumen?"
                           required>
                    @error('value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="type" class="block text-gray-700 font-medium mb-2">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-500 mb-2">Pilih kapan quick response ini akan ditampilkan</p>
                    <select name="type" 
                            id="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror"
                            required>
                        <option value="welcome" {{ old('type', $quickResponse->type) == 'welcome' ? 'selected' : '' }}>
                            Welcome - Saat user baru mulai chat
                        </option>
                        <option value="general" {{ old('type', $quickResponse->type) == 'general' ? 'selected' : '' }}>
                            General - Setelah bot memberikan respon
                        </option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="order" value="{{ $quickResponse->order }}">

                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active"
                               {{ old('is_active', $quickResponse->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <div>
                            <span class="text-gray-700 font-medium">Aktif</span>
                            <p class="text-sm text-gray-500">Quick response akan langsung aktif dan ditampilkan di chatbot</p>
                        </div>
                    </label>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition inline-flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Update Quick Response
                    </button>
                    <a href="{{ route('quick-responses.index') }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition inline-flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function fillForm(intentName) {
            document.getElementById('title').value = intentName;
            document.getElementById('value').value = intentName;
            // Scroll to form
            document.getElementById('title').scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.getElementById('title').focus();
        }
    </script>
@endsection
