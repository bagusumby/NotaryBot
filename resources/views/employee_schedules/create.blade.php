@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Staff')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <i class="fas fa-calendar-plus text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Tambah Jadwal Staff</h2>
                            <p class="text-blue-100 text-sm mt-0.5">Atur jadwal kerja untuk staff</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Info Alert -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div>
                                <p class="text-sm text-blue-900 font-medium">Tips Penggunaan</p>
                                <p class="text-sm text-blue-700 mt-1">Anda bisa memilih beberapa hari sekaligus untuk mengatur jadwal yang sama dalam satu waktu.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">
                        <h6 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-magic text-purple-600"></i>
                            <span>Template Cepat</span>
                        </h6>
                        <p class="text-gray-600 text-sm mb-4">Pilih template jadwal yang sudah tersedia untuk mempercepat input</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button type="button"
                                class="flex flex-col items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg hover:bg-blue-100 transition-colors"
                                onclick="applyTemplate('weekday')">
                                <i class="fas fa-briefcase text-xl"></i>
                                <span class="text-xs font-medium">Senin-Jumat<br>(09:00-17:00)</span>
                            </button>
                            <button type="button"
                                class="flex flex-col items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg hover:bg-green-100 transition-colors"
                                onclick="applyTemplate('fullweek')">
                                <i class="fas fa-calendar text-xl"></i>
                                <span class="text-xs font-medium">Senin-Sabtu<br>(09:00-17:00)</span>
                            </button>
                            <button type="button"
                                class="flex flex-col items-center gap-2 bg-cyan-50 border border-cyan-200 text-cyan-700 px-4 py-3 rounded-lg hover:bg-cyan-100 transition-colors"
                                onclick="applyTemplate('shift1')">
                                <i class="fas fa-sun text-xl"></i>
                                <span class="text-xs font-medium">Shift Pagi<br>(08:00-16:00)</span>
                            </button>
                            <button type="button"
                                class="flex flex-col items-center gap-2 bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-lg hover:bg-orange-100 transition-colors"
                                onclick="applyTemplate('shift2')">
                                <i class="fas fa-moon text-xl"></i>
                                <span class="text-xs font-medium">Shift Sore<br>(13:00-21:00)</span>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('employee-schedules.store') }}" method="POST">
                        @csrf

                        <!-- Staff Selection -->
                        <div class="mb-6">
                            <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-600"></i> Pilih Staff
                                <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm @error('user_id') border-red-500 @enderror"
                                id="user_id" name="user_id" required>
                                <option value="">-- Pilih Staff --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', request('user_id')) == $user->id ? 'selected' : '' }}>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar-week text-blue-600"></i> Pilih Hari
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
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
                                
                                <!-- Day Checkboxes -->
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-4">
                                    @foreach ($dayNames as $value => $label)
                                        <label class="day-checkbox-label relative flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all
                                            {{ in_array($value, (array) $selectedDay) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300 hover:bg-gray-50' }}">
                                            <input type="checkbox" name="days[]" value="{{ $value }}"
                                                id="day_{{ $value }}"
                                                class="day-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                {{ in_array($value, (array) $selectedDay) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-semibold text-gray-900">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Quick Select Buttons -->
                                <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200">
                                    <button type="button"
                                        class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-200 transition-colors"
                                        onclick="selectWeekdays()">
                                        <i class="fas fa-briefcase"></i> Senin-Jumat
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-200 transition-colors"
                                        onclick="selectWeekend()">
                                        <i class="fas fa-home"></i> Sabtu-Minggu
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-200 transition-colors"
                                        onclick="selectAll()">
                                        <i class="fas fa-check-double"></i> Semua Hari
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors"
                                        onclick="clearAll()">
                                        <i class="fas fa-times"></i> Hapus Pilihan
                                    </button>
                                </div>
                            </div>
                            @error('days')
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
                                    id="start_time" name="start_time" value="{{ old('start_time', '09:00') }}" required>
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
                                    id="end_time" name="end_time" value="{{ old('end_time', '17:00') }}" required>
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
                                    {{ old('is_active', true) ? 'checked' : '' }}>
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
                                <span>Simpan Jadwal</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Employee Schedule Script Loaded');
            
            // Add event listeners to all day checkboxes
            const checkboxes = document.querySelectorAll('.day-checkbox');
            console.log('Found checkboxes:', checkboxes.length);
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const label = this.closest('.day-checkbox-label');
                    if (this.checked) {
                        label.classList.add('border-blue-500', 'bg-blue-50');
                        label.classList.remove('border-gray-200');
                    } else {
                        label.classList.remove('border-blue-500', 'bg-blue-50');
                        label.classList.add('border-gray-200');
                    }
                });
            });
        });

        function updateCheckboxUI(checkbox) {
            if (!checkbox) return;
            const label = checkbox.closest('.day-checkbox-label');
            if (!label) return;
            
            if (checkbox.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
                label.classList.remove('border-gray-200');
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-200');
            }
        }

        function selectWeekdays() {
            clearAll();
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'].forEach(day => {
                const checkbox = document.getElementById('day_' + day);
                if (checkbox) {
                    checkbox.checked = true;
                    updateCheckboxUI(checkbox);
                }
            });
        }

        function selectWeekend() {
            clearAll();
            ['saturday', 'sunday'].forEach(day => {
                const checkbox = document.getElementById('day_' + day);
                if (checkbox) {
                    checkbox.checked = true;
                    updateCheckboxUI(checkbox);
                }
            });
        }

        function selectAll() {
            document.querySelectorAll('.day-checkbox').forEach(checkbox => {
                checkbox.checked = true;
                updateCheckboxUI(checkbox);
            });
        }

        function clearAll() {
            document.querySelectorAll('.day-checkbox').forEach(checkbox => {
                checkbox.checked = false;
                updateCheckboxUI(checkbox);
            });
        }

        function applyTemplate(template) {
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            
            if (!startTimeInput || !endTimeInput) {
                console.error('Time inputs not found');
                return;
            }
            
            switch (template) {
                case 'weekday':
                    selectWeekdays();
                    startTimeInput.value = '09:00';
                    endTimeInput.value = '17:00';
                    break;
                case 'fullweek':
                    clearAll();
                    ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(day => {
                        const checkbox = document.getElementById('day_' + day);
                        if (checkbox) {
                            checkbox.checked = true;
                            updateCheckboxUI(checkbox);
                        }
                    });
                    startTimeInput.value = '09:00';
                    endTimeInput.value = '17:00';
                    break;
                case 'shift1':
                    selectWeekdays();
                    startTimeInput.value = '08:00';
                    endTimeInput.value = '16:00';
                    break;
                case 'shift2':
                    selectWeekdays();
                    startTimeInput.value = '13:00';
                    endTimeInput.value = '21:00';
                    break;
            }
        }
    </script>
@endsection
