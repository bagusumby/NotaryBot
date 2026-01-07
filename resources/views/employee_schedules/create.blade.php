@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Staff')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl">
                    <h5 class="text-lg font-semibold"><i class="fas fa-calendar-plus"></i> Tambah Jadwal Staff</h5>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-info-circle"></i> <strong>Tips:</strong> Anda bisa pilih beberapa hari sekaligus
                        untuk mengatur jadwal yang sama.
                    </div>

                    <!-- Quick Templates -->
                    <div class="bg-gray-50 border rounded-lg p-4 mb-6">
                        <h6 class="font-semibold mb-2"><i class="fas fa-magic"></i> Template Cepat</h6>
                        <p class="text-gray-600 text-sm mb-3">Gunakan template jadwal yang sudah tersedia untuk mempercepat
                            input</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded hover:bg-blue-200 text-sm"
                                onclick="applyTemplate('weekday')">
                                <i class="fas fa-briefcase"></i> Senin-Jumat (9-5)
                            </button>
                            <button type="button"
                                class="bg-green-100 text-green-700 px-3 py-1.5 rounded hover:bg-green-200 text-sm"
                                onclick="applyTemplate('fullweek')">
                                <i class="fas fa-calendar"></i> Senin-Sabtu (9-5)
                            </button>
                            <button type="button"
                                class="bg-cyan-100 text-cyan-700 px-3 py-1.5 rounded hover:bg-cyan-200 text-sm"
                                onclick="applyTemplate('shift1')">
                                <i class="fas fa-sun"></i> Shift Pagi (8-4)
                            </button>
                            <button type="button"
                                class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded hover:bg-yellow-200 text-sm"
                                onclick="applyTemplate('shift2')">
                                <i class="fas fa-cloud"></i> Shift Siang (1-9)
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('employee-schedules.store') }}" method="POST">
                        @csrf

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
                                        {{ old('user_id', request('user_id')) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-week"></i> Pilih Hari <span class="text-red-500">*</span>
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
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
                                        $selectedDay = old('days', request('day') ? [request('day')] : []);
                                    @endphp
                                    @foreach ($dayNames as $value => $label)
                                        <div>
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    type="checkbox" name="days[]" value="{{ $value }}"
                                                    id="day_{{ $value }}"
                                                    {{ in_array($value, (array) $selectedDay) ? 'checked' : '' }}>
                                                <span class="font-semibold text-gray-700">{{ $label }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button"
                                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200"
                                        onclick="selectWeekdays()">
                                        <i class="fas fa-briefcase"></i> Senin - Jumat
                                    </button>
                                    <button type="button"
                                        class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-300"
                                        onclick="selectWeekend()">
                                        <i class="fas fa-home"></i> Sabtu - Minggu
                                    </button>
                                    <button type="button"
                                        class="bg-gray-700 text-white px-3 py-1 rounded text-sm hover:bg-gray-800"
                                        onclick="selectAll()">
                                        <i class="fas fa-check-double"></i> Pilih Semua
                                    </button>
                                    <button type="button"
                                        class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200"
                                        onclick="clearAll()">
                                        <i class="fas fa-times"></i> Bersihkan
                                    </button>
                                </div>
                            </div>
                            @error('days')
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
                                    id="start_time" name="start_time" value="{{ old('start_time', '09:00') }}" required>
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
                                    id="end_time" name="end_time" value="{{ old('end_time', '17:00') }}" required>
                                @error('end_time')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center space-x-3">
                                <input class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
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
                            <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectWeekdays() {
            clearAll();
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'].forEach(day => {
                document.getElementById('day_' + day).checked = true;
            });
        }

        function selectWeekend() {
            clearAll();
            ['saturday', 'sunday'].forEach(day => {
                document.getElementById('day_' + day).checked = true;
            });
        }

        function selectAll() {
            document.querySelectorAll('input[name="days[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function clearAll() {
            document.querySelectorAll('input[name="days[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        function applyTemplate(template) {
            switch (template) {
                case 'weekday':
                    selectWeekdays();
                    document.getElementById('start_time').value = '09:00';
                    document.getElementById('end_time').value = '17:00';
                    break;
                case 'fullweek':
                    clearAll();
                    ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(day => {
                        document.getElementById('day_' + day).checked = true;
                    });
                    document.getElementById('start_time').value = '09:00';
                    document.getElementById('end_time').value = '17:00';
                    break;
                case 'shift1':
                    selectWeekdays();
                    document.getElementById('start_time').value = '08:00';
                    document.getElementById('end_time').value = '16:00';
                    break;
                case 'shift2':
                    selectWeekdays();
                    document.getElementById('start_time').value = '13:00';
                    document.getElementById('end_time').value = '21:00';
                    break;
            }
        }
    </script>
@endsection
