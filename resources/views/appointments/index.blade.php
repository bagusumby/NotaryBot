@extends('layouts.dashboard')

@section('title', 'Appointments List')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-calendar-check mr-2 text-blue-600"></i>Appointments List
            </h2>
            <a href="{{ route('appointments.create') }}"
                class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus"></i>
                Create New Appointment
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg" id="successAlert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="font-semibold text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">#</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Booking Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Booking Time</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Assigned To</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $loop->iteration + ($appointments->currentPage() - 1) * $appointments->perPage() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>{{ $appointment->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $appointment->phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <i class="fas fa-clock text-gray-400 mr-1"></i>{{ $appointment->booking_time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($appointment->employee)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-user-tie mr-1"></i>{{ $appointment->employee->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Confirmed' => 'bg-green-100 text-green-800',
                                            'Cancelled' => 'bg-red-100 text-red-800',
                                            'Completed' => 'bg-blue-100 text-blue-800',
                                        ];
                                        $badgeClass =
                                            $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a href="{{ route('appointments.show', $appointment->id) }}"
                                            class="text-blue-600 hover:text-blue-800 transition-colors" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('appointments.edit', $appointment->id) }}"
                                            class="text-yellow-600 hover:text-yellow-800 transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">No appointments found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($appointments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $appointments->links() }}
                </div>
            @endif
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
