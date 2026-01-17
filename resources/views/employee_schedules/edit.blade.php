@extends('layouts.dashboard')

@section('title', 'Edit Jadwal Staff')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl">
                    <h5 class="text-lg font-semibold"><i class="fas fa-edit"></i> Edit Jadwal Staff</h5>
                </div>
                <div class="p-6">
                    <form action="{{ route('employee-schedules.update', $employeeSchedule) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="user_id" class="block font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user"></i> Staff <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('user_id') border-red-500 @enderror"
                                id="user_id" name="user_id" required>
                                <option value="">Pilih Staff</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $employeeSchedule->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="day_of_week" class="block font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-day"></i> Hari <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('day_of_week') border-red-500 @enderror"
                                id="day_of_week" name="day_of_week" required>
                                <option value="">Pilih Hari</option>
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
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="start_time" class="block font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clock"></i> Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('start_time') border-red-500 @enderror"
                                    id="start_time" name="start_time"
                                    value="{{ old('start_time', \Carbon\Carbon::parse($employeeSchedule->start_time)->format('H:i')) }}"
                                    required>
                                @error('start_time')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="end_time" class="block font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clock"></i> Jam Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('end_time') border-red-500 @enderror"
                                    id="end_time" name="end_time"
                                    value="{{ old('end_time', \Carbon\Carbon::parse($employeeSchedule->end_time)->format('H:i')) }}"
                                    required>
                                @error('end_time')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center space-x-3">
                                <input class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $employeeSchedule->is_active) ? 'checked' : '' }}>
                                <div>
                                    <span class="font-semibold text-gray-700">
                                        <i class="fas fa-toggle-on"></i> Jadwal Aktif
                                    </span>
                                    <small class="block text-gray-500 text-sm">Nonaktifkan jika staff sedang
                                        cuti/libur</small>
                                </div>
                            </label>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('employee-schedules.index') }}"
                                class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save"></i> Update Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
