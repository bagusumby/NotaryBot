@extends('layouts.dashboard')

@section('title', 'Jadwal Kerja Staff')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <i class="fas fa-calendar-week text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Jadwal Kerja Staff</h1>
                                <p class="text-sm text-gray-500 mt-0.5">Kelola jadwal kerja staff per hari</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('employee-schedules.create') }}"
                        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Jadwal</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 rounded-lg p-4 shadow-sm" id="successAlert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-700 text-lg"></i>
                        </div>
                        <p class="ml-3 text-sm font-semibold text-green-900">{{ session('success') }}</p>
                        <button onclick="document.getElementById('successAlert').remove()" class="ml-auto text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if ($users->count() == 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                    <i class="fas fa-info-circle text-blue-600 text-3xl mb-3"></i>
                    <p class="text-blue-800 mb-2">Belum ada staff yang tersedia.</p>
                    <p class="text-blue-600 text-sm">
                        Silakan tambahkan staff terlebih dahulu di 
                        <a href="{{ route('user-management') }}" class="underline font-medium hover:text-blue-800">User Management</a>.
                    </p>
                </div>
            @else
                @foreach ($users as $user)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
                        <!-- Staff Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600 bg-opacity-20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-tie text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-lg font-semibold text-black">{{ $user->name }}</h5>
                                        <p class="text-black text-sm opacity-90">{{ $user->email }}</p>
                                    </div>
                                </div>
                                @if ($user->schedules->count() == 0)
                                    <a href="{{ route('employee-schedules.create') }}?user_id={{ $user->id }}"
                                        class="inline-flex items-center gap-2 bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                                        <i class="fas fa-plus"></i>
                                        <span>Atur Jadwal</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Schedule Content -->
                        <div class="p-6">
                            @if ($user->schedules->count() > 0)
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
                                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                    $schedulesByDay = $user->schedules->keyBy('day_of_week');
                                @endphp

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    @foreach ($days as $day)
                                        @php
                                            $schedule = $schedulesByDay->get($day);
                                        @endphp
                                        <div class="border rounded-lg p-4 transition-all hover:shadow-md
                                            {{ $schedule && $schedule->is_active ? 'border-green-400 bg-green-50' : 'border-gray-200 bg-white' }}">
                                            
                                            <!-- Day Header -->
                                            <div class="flex items-center justify-between mb-3">
                                                <h6 class="font-bold text-sm {{ $schedule && $schedule->is_active ? 'text-green-900' : 'text-gray-900' }}">{{ $dayNames[$day] }}</h6>
                                                @if ($schedule)
                                                    @if ($schedule->is_active)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-500 text-white">
                                                            <i class="fas fa-check-circle text-xs mr-1"></i>Aktif
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-400 text-white">
                                                            <i class="fas fa-ban text-xs mr-1"></i>Off
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">
                                                        <i class="fas fa-calendar-times text-xs mr-1"></i>Libur
                                                    </span>
                                                @endif
                                            </div>

                                            @if ($schedule)
                                                <!-- Schedule Time -->
                                                <div class="mb-4">
                                                    <p class="text-xs font-medium mb-1 {{ $schedule->is_active ? 'text-green-800' : 'text-gray-600' }}">Jam Kerja</p>
                                                    <div class="flex items-center gap-2 font-bold {{ $schedule->is_active ? 'text-green-800' : 'text-blue-700' }}">
                                                        <i class="fas fa-clock text-sm"></i>
                                                        <span class="text-sm">
                                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex gap-2">
                                                    <a href="{{ route('employee-schedules.edit', $schedule) }}"
                                                        class="flex-1 text-center bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition-colors text-xs font-semibold shadow-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('employee-schedules.destroy', $schedule) }}"
                                                        method="POST" class="flex-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors text-xs font-semibold"
                                                            onclick="return confirm('Hapus jadwal {{ $dayNames[$day] }}?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <!-- No Schedule -->
                                                <p class="text-gray-400 text-xs mb-4 text-center py-2">Tidak ada jadwal</p>
                                                <a href="{{ route('employee-schedules.create') }}?user_id={{ $user->id }}&day={{ $day }}"
                                                    class="block text-center bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors text-xs font-medium">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- No Schedules Yet -->
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                        <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                                    </div>
                                    <h5 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Jadwal</h5>
                                    <p class="text-gray-500 mb-6 text-sm">Staff ini belum memiliki jadwal kerja.</p>
                                    <a href="{{ route('employee-schedules.create') }}?user_id={{ $user->id }}"
                                        class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                        <i class="fas fa-plus"></i>
                                        <span>Buat Jadwal Sekarang</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
