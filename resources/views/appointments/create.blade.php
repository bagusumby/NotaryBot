@extends('layouts.dashboard')

@section('title', 'Create Appointment')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-plus-circle mr-2 text-blue-600"></i>Create New Appointment
            </h2>
            <a href="{{ route('appointments.index') }}"
                class="flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="employee_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Assign to Employee <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('employee_id') border-red-500 @enderror"
                        id="employee_id" name="employee_id">
                        <option value="">Auto Assign</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ ucfirst($employee->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Client Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                        id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="booking_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Booking Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('booking_date') border-red-500 @enderror"
                            id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required>
                        @error('booking_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="booking_time" class="block text-sm font-semibold text-gray-700 mb-2">
                            Booking Time <span class="text-red-500">*</span>
                        </label>
                        <select
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('booking_time') border-red-500 @enderror"
                            id="booking_time" name="booking_time" required disabled>
                            <option value="">Select date first</option>
                        </select>
                        @error('booking_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500" id="slot-info"></p>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                        id="status" name="status" required>
                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Confirmed" {{ old('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                        id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('appointments.index') }}"
                        class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                        <i class="fas fa-save"></i>
                        Create Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load available slots when date or employee changes
            $('#booking_date, #employee_id').on('change', function() {
                loadAvailableSlots();
            });

            function loadAvailableSlots() {
                const date = $('#booking_date').val();
                const employeeId = $('#employee_id').val();

                if (!date) {
                    $('#booking_time').prop('disabled', true).html('<option value="">Select date first</option>');
                    $('#slot-info').text('').removeClass('text-green-600 text-red-600');
                    return;
                }

                // Show loading
                $('#booking_time').prop('disabled', true).html('<option value="">Loading slots...</option>');
                $('#slot-info').text('Loading available time slots...').removeClass('text-green-600 text-red-600')
                    .addClass('text-gray-500');

                $.ajax({
                    url: '{{ route('appointments.getSlots') }}',
                    method: 'GET',
                    data: {
                        date: date,
                        employee_id: employeeId
                    },
                    success: function(response) {
                        $('#booking_time').prop('disabled', false).empty();

                        if (response.slots && response.slots.length > 0) {
                            $('#booking_time').append('<option value="">Select time slot</option>');
                            response.slots.forEach(function(slot) {
                                $('#booking_time').append(
                                    '<option value="' + slot.time + '">' +
                                    slot.time + ' (' + slot.available_slots +
                                    ' available)' +
                                    '</option>'
                                );
                            });
                            $('#slot-info').text('Available slots loaded successfully')
                                .removeClass('text-red-600 text-gray-500').addClass('text-green-600');
                        } else {
                            $('#booking_time').append('<option value="">' + (response.message ||
                                'No available slots') + '</option>');
                            $('#slot-info').text(response.message || 'No available slots for this date')
                                .removeClass('text-green-600 text-gray-500').addClass('text-red-600');
                        }
                    },
                    error: function() {
                        $('#booking_time').prop('disabled', false).html(
                            '<option value="">Error loading slots</option>');
                        $('#slot-info').text('Error loading slots. Please try again.')
                            .removeClass('text-green-600 text-gray-500').addClass('text-red-600');
                    }
                });
            }
        });
    </script>
@endpush
