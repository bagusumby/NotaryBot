@extends('layouts.dashboard')

@section('title', 'User Details')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-user-circle mr-2 text-blue-600"></i>User Details
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user->id) }}"
                    class="flex items-center gap-2 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-edit"></i>
                    Edit User
                </a>
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header with Avatar -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-24 w-24 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-5xl text-blue-600"></i>
                    </div>
                    <div class="ml-6">
                        <h3 class="text-3xl font-bold">{{ $user->name }}</h3>
                        <p class="text-blue-100 mt-1">{{ $user->email }}</p>
                        <div class="mt-2">
                            @if ($user->role === 'superadmin')
                                <span
                                    class="px-4 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-crown mr-1"></i>Superadmin
                                </span>
                            @elseif($user->role === 'admin')
                                <span
                                    class="px-4 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-user-shield mr-1"></i>Admin
                                </span>
                            @else
                                <span
                                    class="px-4 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-user mr-1"></i>Staff
                                </span>
                            @endif

                            @if ($user->id === auth()->id())
                                <span
                                    class="ml-2 px-4 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-star mr-1"></i>Current User
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-8">
                <h4 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>User Information
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ID -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">User ID</label>
                        <p class="text-lg font-semibold text-gray-900">#{{ $user->id }}</p>
                    </div>

                    <!-- Full Name -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                    </div>

                    <!-- Email -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                    </div>

                    <!-- Role -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                        <p class="text-lg font-semibold text-gray-900 capitalize">{{ $user->role }}</p>
                    </div>

                    <!-- Created At -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Account Created</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    <!-- Updated At -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="border-t border-gray-200 bg-gray-50 px-8 py-6">
                <div class="flex justify-between items-center">
                    <div class="flex gap-3">
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition-colors inline-flex items-center">
                            <i class="fas fa-edit mr-2"></i>Edit User
                        </a>
                    </div>

                    @if ($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors inline-flex items-center">
                                <i class="fas fa-trash mr-2"></i>Delete User
                            </button>
                        </form>
                    @else
                        <div class="text-sm text-gray-500 italic">
                            <i class="fas fa-info-circle mr-1"></i>You cannot delete your own account
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
