@extends('layouts.dashboard')

@section('title', 'Jadwal Kerja Staff')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2><i class="fas fa-calendar-week"></i> Jadwal Kerja Staff Notaris</h2>
                        <p class="text-gray-600 mb-0">Atur jadwal kerja staff per hari dalam seminggu</p>
                    </div>
                    <a href="{{ route('employee-schedules.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus"></i> Tambah Jadwal Baru
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if ($users->count() == 0)
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
                <i class="fas fa-info-circle"></i> Belum ada staff. Silakan tambahkan staff terlebih dahulu di menu <a
                    href="{{ route('user-management') }}" class="underline">User Management</a>.
            </div>
        @else
            @foreach ($users as $user)
                <div class="bg-white rounded-xl shadow-sm mb-4">
                    <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl">
                        <div class="flex justify-content-between align-items-center">
                            <div>
                                <h5 class="text-lg font-semibold mb-1">
                                    <i class="fas fa-user-tie"></i> {{ $user->name }}
                                </h5>
                                <small class="text-blue-100">{{ $user->email }}</small>
                            </div>
                            @if ($user->schedules->count() == 0)
                                <a href="{{ route('employee-schedules.create') }}?user_id={{ $user->id }}"
                                    class="bg-white text-blue-600 px-3 py-1.5 rounded-lg hover:bg-gray-100 text-sm">
                                    <i class="fas fa-plus"></i> Atur Jadwal
                                </a>
                            @endif
                        </div>
                    </div>
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

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach ($days as $day)
                                    @php
                                        $schedule = $schedulesByDay->get($day);
                                    @endphp
                                    <div
                                        class="border rounded-lg {{ $schedule && $schedule->is_active ? 'border-green-500' : 'border-gray-300' }} p-4 hover:shadow-md transition-shadow">
                                        <div class="flex justify-content-between align-items-start mb-3">
                                            <h6 class="font-bold text-gray-900">{{ $dayNames[$day] }}</h6>
                                            @if ($schedule)
                                                @if ($schedule->is_active)
                                                    <span
                                                        class="bg-green-500 text-white px-2 py-1 rounded text-xs">Aktif</span>
                                                @else
                                                    <span
                                                        class="bg-gray-400 text-white px-2 py-1 rounded text-xs">Off</span>
                                                @endif
                                            @else
                                                <span
                                                    class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">Libur</span>
                                            @endif
                                        </div>

                                        @if ($schedule)
                                            <div class="mb-3">
                                                <div class="text-gray-500 text-sm mb-1">Jam Kerja:</div>
                                                <div class="font-bold text-blue-600">
                                                    <i class="fas fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('employee-schedules.edit', $schedule) }}"
                                                    class="flex-1 text-center bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 text-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('employee-schedules.destroy', $schedule) }}"
                                                    method="POST" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full bg-red-500 text-white px-3 py-1.5 rounded hover:bg-red-600 text-sm"
                                                        onclick="return confirm('Hapus jadwal {{ $dayNames[$day] }}?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <p class="text-gray-500 text-sm mb-3">Tidak ada jadwal</p>
                                            <a href="{{ route('employee-schedules.create') }}?user_id={{ $user->id }}&day={{ $day }}"
                                                class="block text-center bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 text-sm">
                                                <i class="fas fa-plus"></i> Tambah Jadwal
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-calendar-times text-6xl text-gray-400 mb-4"></i>
                                <h5 class="text-gray-600 mb-2">Belum Ada Jadwal</h5>
                                <p class="text-gray-500 mb-4">Staff ini belum memiliki jadwal kerja.</p>
                                <a href="{{ route('employee-schedules.create') }}?user_id={{ $user->id }}"
                                    class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 inline-block">
                                    <i class="fas fa-plus"></i> Buat Jadwal Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
