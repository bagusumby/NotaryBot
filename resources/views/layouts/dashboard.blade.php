<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard - Notary Services')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar - Desktop -->
        <aside class="hidden lg:flex flex-col w-64 bg-white shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-shield-alt w-8 h-8 text-blue-600"></i>
                    <span class="text-xl text-gray-900">Notary Admin</span>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Logged in as</p>
                    <p class="text-gray-900">{{ session('user.name', 'Admin') }}</p>
                    <p class="text-xs text-blue-600 uppercase mt-1">{{ session('user.role', 'staff') }}</p>
                </div>
            </div>

            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-th-large w-5 h-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('schedule-management') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('schedule-management') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-calendar w-5 h-5"></i>
                            <span>Schedule Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('appointments') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('appointments') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-calendar-check w-5 h-5"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('reports') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-file-alt w-5 h-5"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reviews') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('reviews') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-star w-5 h-5"></i>
                            <span>Report Reviews</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('unanswered-questions') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('unanswered-questions') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-question-circle w-5 h-5"></i>
                            <span>Unanswered Questions</span>
                        </a>
                    </li>
                    @if (session('user.role') === 'superadmin')
                        <li>
                            <a href="{{ route('user-management') }}"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('user-management') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-users w-5 h-5"></i>
                                <span>User Management</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bot-training') }}"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('bot-training') || request()->routeIs('intents.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-robot w-5 h-5"></i>
                                <span>Bot Training</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('quick-responses.index') }}"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('quick-responses.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-bolt w-5 h-5"></i>
                                <span>Quick Response</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            <div class="p-4 border-t border-gray-200">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5 h-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar-overlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"
            onclick="toggleMobileSidebar()"></div>
        <aside id="mobile-sidebar"
            class="lg:hidden fixed left-0 top-0 bottom-0 w-64 bg-white shadow-lg z-50 flex flex-col -translate-x-full transition-transform">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt w-8 h-8 text-blue-600"></i>
                        <span class="text-xl text-gray-900">Notary Admin</span>
                    </div>
                    <button onclick="toggleMobileSidebar()">
                        <i class="fas fa-times w-6 h-6 text-gray-600"></i>
                    </button>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Logged in as</p>
                    <p class="text-gray-900">{{ session('user.name', 'Admin') }}</p>
                    <p class="text-xs text-blue-600 uppercase mt-1">{{ session('user.role', 'staff') }}</p>
                </div>
            </div>

            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-th-large w-5 h-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('schedule-management') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('schedule-management') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-calendar w-5 h-5"></i>
                            <span>Schedule Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('appointments') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('appointments') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-calendar-check w-5 h-5"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('reports') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-file-alt w-5 h-5"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reviews') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('reviews') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-star w-5 h-5"></i>
                            <span>Report Reviews</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('unanswered-questions') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('unanswered-questions') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-question-circle w-5 h-5"></i>
                            <span>Unanswered Questions</span>
                        </a>
                    </li>
                    @if (session('user.role') === 'superadmin')
                        <li>
                            <a href="{{ route('user-management') }}"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('user-management') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-users w-5 h-5"></i>
                                <span>User Management</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bot-training') }}"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('bot-training') || request()->routeIs('intents.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-robot w-5 h-5"></i>
                                <span>Bot Training</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('quick-responses.index') }}"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('quick-responses.*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-bolt w-5 h-5"></i>
                                <span>Quick Response</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            <div class="p-4 border-t border-gray-200">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5 h-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Mobile Header -->
            <header class="lg:hidden bg-white shadow-sm p-4 flex items-center justify-between">
                <button onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars w-6 h-6 text-gray-600"></i>
                </button>
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt w-6 h-6 text-blue-600"></i>
                    <span class="text-gray-900">Notary Admin</span>
                </div>
                <div class="w-6"></div>
            </header>

            <main class="flex-1 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>

</html>
