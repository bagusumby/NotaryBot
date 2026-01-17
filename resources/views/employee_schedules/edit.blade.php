@extends('layouts.dashboard')

@section('title', 'Edit Jadwal Staff')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('employee-schedules.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="text-sm font-medium">Kembali ke Daftar Jadwal</span>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 rounded-t-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Edit Jadwal Staff</h2>
                            <p class="text-blue-100 text-sm mt-0.5">Ubah jadwal kerja staff</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('employee-schedules.update', $employeeSchedule) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Staff Selection -->
                        <div class="mb-6">
                            <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-600"></i> Staff
                                <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm @error('user_id') border-red-500 @enderror"
                                id="user_id" name="user_id" required>
                                <option value="">-- Pilih Staff --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $employeeSchedule->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Day Selection -->
                        <div class="mb-6">
                            <label for="day_of_week" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-day text-blue-600"></i> Hari
                                <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm @error('day_of_week') border-red-500 @enderror"
                                id="day_of_week" name="day_of_week" required>
                                <option value="">-- Pilih Hari --</option>
                                @php
                                    $dayNames = [
                                        'monday' => 'Senin',
                                        'tuesday' => 'Selasa',
                                        'wednesday' => 'Rabu',
                                        'thursday' => 'Kamis',
                                        'friday' => 'Jumat',
                                        'saturday' => 'Sabtu',
                                        'sunday' => 'Minggu',
                                    ];
                                @endphp
                                @foreach ($dayNames as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('day_of_week', $employeeSchedule->day_of_week) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clock text-blue-600"></i> Jam Mulai
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm @error('start_time') border-red-500 @enderror"
                                    id="start_time" name="start_time"
                                    value="{{ old('start_time', \Carbon\Carbon::parse($employeeSchedule->start_time)->format('H:i')) }}"
                                    required>
                                @error('start_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clock text-blue-600"></i> Jam Selesai
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm @error('end_time') border-red-500 @enderror"
                                    id="end_time" name="end_time"
                                    value="{{ old('end_time', \Carbon\Carbon::parse($employeeSchedule->end_time)->format('H:i')) }}"
                                    required>
                                @error('end_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5"
                                    {{ old('is_active', $employeeSchedule->is_active) ? 'checked' : '' }}>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700 block">Jadwal Aktif</span>
                                    <p class="text-xs text-gray-500 mt-0.5">Nonaktifkan jika staff sedang cuti/libur</p>
                                </div>
                            </label>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('employee-schedules.index') }}"
                                class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md font-medium text-sm">
                                <i class="fas fa-save"></i>
                                <span>Update Jadwal</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
