@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-user-edit mr-2 text-blue-600"></i>Edit User
            </h2>
            <a href="{{ route('users.index') }}"
                class="flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Full Name -->
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        id="name" name="name" value="{{ old('name', $user->name) }}" required
                        placeholder="Enter full name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        id="email" name="email" value="{{ old('email', $user->email) }}" required
                        placeholder="Enter email address">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Fields (Optional) -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-blue-800 mb-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Leave password fields empty if you don't want to change the password.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-gray-700 font-semibold mb-2">
                                New Password
                            </label>
                            <input type="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                id="password" name="password" placeholder="Min. 8 characters">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">
                                Confirm New Password
                            </label>
                            <input type="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                        </div>
                    </div>
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role" class="block text-gray-700 font-semibold mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror"
                        id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>
                            Superadmin</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Superadmin:</strong> Full access | <strong>Admin:</strong> Manage users & settings |
                        <strong>Staff:</strong> Basic access
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('users.index') }}"
                        class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                        <i class="fas fa-save"></i>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
