@extends('layouts.dashboard')

@section('title', 'Appointment Details')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Appointment Details
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('appointments.edit', $appointment->id) }}"
                    class="flex items-center gap-2 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-edit"></i>
                    Edit Appointment
                </a>
                <a href="{{ route('appointments.index') }}"
                    class="flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4 text-white">
                        <h3 class="text-2xl font-bold">{{ $appointment->name }}</h3>
                        <p class="text-blue-100">{{ $appointment->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Appointment Info -->
            <div class="px-8 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Appointment ID</h4>
                        <p class="text-lg font-medium text-gray-900">#{{ $appointment->id }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Status</h4>
                        @php
                            $statusColors = [
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'Confirmed' => 'bg-green-100 text-green-800',
                                'Cancelled' => 'bg-red-100 text-red-800',
                                'Completed' => 'bg-blue-100 text-blue-800',
                            ];
                            $badgeClass = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $badgeClass }}">
                            {{ $appointment->status }}
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-envelope mr-1"></i>Email
                        </h4>
                        <p class="text-lg text-gray-900">{{ $appointment->email }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-phone mr-1"></i>Phone
                        </h4>
                        <p class="text-lg text-gray-900">{{ $appointment->phone }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-calendar mr-1"></i>Booking Date
                        </h4>
                        <p class="text-lg text-gray-900">{{ $appointment->booking_date->format('l, d F Y') }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-clock mr-1"></i>Booking Time
                        </h4>
                        <p class="text-lg text-gray-900">{{ $appointment->booking_time }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-user-tie mr-1"></i>Assigned Employee
                        </h4>
                        @if ($appointment->employee)
                            <div class="flex items-center mt-2">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-purple-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-lg font-medium text-gray-900">{{ $appointment->employee->name }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst($appointment->employee->role) }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400 italic">Not assigned</p>
                        @endif
                    </div>
                </div>

                @if ($appointment->notes)
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase mb-2">
                            <i class="fas fa-sticky-note mr-1"></i>Notes
                        </h4>
                        <p class="text-gray-700 whitespace-pre-line">{{ $appointment->notes }}</p>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-semibold">Created:</span>
                            {{ $appointment->created_at->format('d M Y, H:i') }}
                        </div>
                        <div>
                            <span class="font-semibold">Last Updated:</span>
                            {{ $appointment->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="bg-gray-50 px-8 py-4 flex justify-between items-center border-t border-gray-200">
                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash"></i>
                        Delete Appointment
                    </button>
                </form>

                <a href="{{ route('appointments.edit', $appointment->id) }}"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit"></i>
                    Edit Appointment
                </a>
            </div>
        </div>
    </div>
@endsection
