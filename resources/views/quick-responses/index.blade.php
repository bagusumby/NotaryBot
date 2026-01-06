@extends('layouts.dashboard')

@section('title', 'Quick Response Management - Notary Services')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl text-gray-900 mb-2">Quick Response Management</h1>
                <p class="text-gray-600">Kelola quick response yang muncul sebagai chips di chatbot</p>
            </div>
            <a href="{{ route('quick-responses.create') }}" 
               class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition inline-flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Tambah Quick Response
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Saran dari Intent yang Sering Ditanyakan -->
        @if($popularIntents->count() > 0)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="bg-blue-600 p-3 rounded-lg">
                    <i class="fas fa-lightbulb text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg text-gray-900 mb-2">💡 Saran Pertanyaan yang Sering Ditanyakan</h3>
                    <p class="text-gray-600 mb-4">Berikut adalah intent yang paling sering muncul. Anda bisa menambahkannya sebagai quick response:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($popularIntents as $intent)
                        <div class="bg-white p-4 rounded-lg border border-blue-200 flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-gray-900 font-medium">{{ $intent->display_name }}</p>
                                <p class="text-sm text-gray-500">Digunakan {{ $intent->usage_count }}x</p>
                            </div>
                            <button onclick="useIntent('{{ addslashes($intent->display_name) }}')" 
                                    class="text-blue-600 hover:text-blue-700 px-3 py-1.5 text-sm border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                <i class="fas fa-plus mr-1"></i> Gunakan
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tab untuk Welcome dan General -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex">
                    <button onclick="showTab('welcome')" 
                            id="tab-welcome"
                            class="px-6 py-4 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition border-b-2 border-transparent">
                        <i class="fas fa-home mr-2"></i>
                        Welcome Chips
                    </button>
                    <button onclick="showTab('general')" 
                            id="tab-general"
                            class="px-6 py-4 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition border-b-2 border-transparent">
                        <i class="fas fa-comment-dots mr-2"></i>
                        General Chips
                    </button>
                </nav>
            </div>

            <!-- Welcome Chips Content -->
            <div id="content-welcome" class="tab-content p-6">
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Welcome Chips:</strong> Ditampilkan saat user baru memulai chat dengan chatbot (loadWelcomeMessage)
                    </p>
                </div>
                @php
                    $welcomeResponses = $quickResponses->where('type', 'welcome');
                @endphp
                
                @if($welcomeResponses->count() > 0)
                    <div class="space-y-3">
                        @foreach($welcomeResponses as $response)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:shadow-sm transition">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="text-gray-500 font-mono text-sm">
                                    #{{ $response->order }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-gray-900 font-medium">{{ $response->title }}</h3>
                                        @if($response->is_active)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                                        @else
                                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">Nonaktif</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">Value: <span class="font-mono bg-gray-200 px-2 py-0.5 rounded">{{ $response->value }}</span></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('quick-responses.edit', $response) }}" 
                                   class="text-blue-600 hover:text-blue-700 px-3 py-2 text-sm border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('quick-responses.destroy', $response) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus quick response ini?')"
                                            class="text-red-600 hover:text-red-700 px-3 py-2 text-sm border border-red-200 rounded-lg hover:bg-red-50 transition">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 mb-4">Belum ada quick response untuk welcome message</p>
                        <a href="{{ route('quick-responses.create') }}" 
                           class="text-blue-600 hover:text-blue-700 font-medium">
                            <i class="fas fa-plus mr-1"></i> Tambah Quick Response Pertama
                        </a>
                    </div>
                @endif
            </div>

            <!-- General Chips Content -->
            <div id="content-general" class="tab-content p-6 hidden">
                <div class="mb-4 bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <p class="text-sm text-purple-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>General Chips:</strong> Ditampilkan setelah bot memberikan respon kepada user
                    </p>
                </div>
                @php
                    $generalResponses = $quickResponses->where('type', 'general');
                @endphp
                
                @if($generalResponses->count() > 0)
                    <div class="space-y-3">
                        @foreach($generalResponses as $response)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:shadow-sm transition">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="text-gray-500 font-mono text-sm">
                                    #{{ $response->order }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-gray-900 font-medium">{{ $response->title }}</h3>
                                        @if($response->is_active)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                                        @else
                                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">Nonaktif</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">Value: <span class="font-mono bg-gray-200 px-2 py-0.5 rounded">{{ $response->value }}</span></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('quick-responses.edit', $response) }}" 
                                   class="text-blue-600 hover:text-blue-700 px-3 py-2 text-sm border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('quick-responses.destroy', $response) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus quick response ini?')"
                                            class="text-red-600 hover:text-red-700 px-3 py-2 text-sm border border-red-200 rounded-lg hover:bg-red-50 transition">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 mb-4">Belum ada quick response untuk general message</p>
                        <a href="{{ route('quick-responses.create') }}" 
                           class="text-blue-600 hover:text-blue-700 font-medium">
                            <i class="fas fa-plus mr-1"></i> Tambah Quick Response Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active state from all tabs
            document.querySelectorAll('nav button').forEach(tab => {
                tab.classList.remove('border-blue-600', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-600');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active state to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');
            activeTab.classList.add('border-blue-600', 'text-blue-600');
        }
        
        function useIntent(intentName) {
            // Redirect ke create page dengan pre-filled intent name
            window.location.href = "{{ route('quick-responses.create') }}?suggestion=" + encodeURIComponent(intentName);
        }
        
        // Set default tab
        showTab('welcome');
    </script>
@endsection
