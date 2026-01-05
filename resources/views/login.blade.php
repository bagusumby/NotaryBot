@extends('layouts.app')

@section('title', 'Login - Notary Services')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4">
                    <i class="fas fa-shield-alt w-10 h-10 text-blue-600"></i>
                </div>
                <h1 class="text-3xl text-white mb-2">Notary Services</h1>
                <p class="text-blue-100">Admin Dashboard Login</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                @if (session('error'))
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg flex items-center gap-2 mb-6">
                        <i class="fas fa-alert-circle w-5 h-5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                placeholder="Enter your email" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="password" name="password"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                placeholder="Enter your password" required />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                        Login
                    </button>
                </form>

                <!-- Demo Accounts -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-600 text-center mb-4">Demo Accounts:</p>
                    <div class="space-y-2">
                        <form action="{{ route('login.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="admin@notary.com">
                            <input type="hidden" name="password" value="admin123">
                            <button type="submit"
                                class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 text-sm">
                                <span>Superadmin</span>
                                <span class="text-gray-500"> - admin@notary.com</span>
                            </button>
                        </form>
                        <form action="{{ route('login.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="staff@notary.com">
                            <input type="hidden" name="password" value="staff123">
                            <button type="submit"
                                class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 text-sm">
                                <span>Staff</span>
                                <span class="text-gray-500"> - staff@notary.com</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('landing') }}" class="text-blue-600 hover:underline">
                        Kembali ke Landing Page
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
