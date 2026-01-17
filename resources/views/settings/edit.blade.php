@extends('layouts.dashboard')

@section('title', 'System Settings')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-cog w-6 h-6 text-gray-700"></i>
                <h1 class="text-2xl font-bold text-gray-800">System Settings</h1>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg" id="successAlert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="font-semibold text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Settings Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Operational Days -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Operational Days</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $days = [
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                            ];
                        @endphp
                        @foreach ($days as $value => $label)
                            <div class="flex items-center">
                                <input type="checkbox" name="operational_days[]" value="{{ $value }}"
                                    id="day_{{ $value }}"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    {{ in_array($value, old('operational_days', $setting->operational_days ?? [])) ? 'checked' : '' }}>
                                <label for="day_{{ $value }}" class="ml-2 text-sm text-gray-700">
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('operational_days')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Working Hours -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_time') border-red-500 @enderror"
                            id="start_time" name="start_time"
                            value="{{ old('start_time', isset($setting->start_time) ? substr($setting->start_time, 0, 5) : '09:00') }}"
                            required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_time') border-red-500 @enderror"
                            id="end_time" name="end_time"
                            value="{{ old('end_time', isset($setting->end_time) ? substr($setting->end_time, 0, 5) : '17:00') }}"
                            required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Session Duration -->
                <div class="mb-6">
                    <label for="session_duration" class="block text-sm font-semibold text-gray-700 mb-2">
                        Session Duration <span class="text-red-500">*</span>
                    </label>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('session_duration') border-red-500 @enderror"
                        id="session_duration" name="session_duration" required>
                        <option value="15"
                            {{ old('session_duration', $setting->session_duration ?? 60) == 15 ? 'selected' : '' }}>15
                            Minutes</option>
                        <option value="30"
                            {{ old('session_duration', $setting->session_duration ?? 60) == 30 ? 'selected' : '' }}>30
                            Minutes</option>
                        <option value="60"
                            {{ old('session_duration', $setting->session_duration ?? 60) == 60 ? 'selected' : '' }}>1 Hour
                        </option>
                        <option value="90"
                            {{ old('session_duration', $setting->session_duration ?? 60) == 90 ? 'selected' : '' }}>1.5
                            Hours</option>
                        <option value="120"
                            {{ old('session_duration', $setting->session_duration ?? 60) == 120 ? 'selected' : '' }}>2
                            Hours</option>
                    </select>
                    @error('session_duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        This determines the duration of each appointment slot
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                        <i class="fas fa-save"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto hide alerts after 3 seconds
            setTimeout(function() {
                const successAlert = document.getElementById('successAlert');
                if (successAlert) {
                    successAlert.style.transition = 'opacity 0.5s';
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 500);
                }
            }, 3000);
        </script>
    @endpush
@endsection
