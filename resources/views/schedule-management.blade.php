@extends('layouts.dashboard')

@section('title', 'Schedule Management - Calendar View')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Schedule Management - Appointments Calendar
            </h2>
            <a href="{{ route('appointments.create') }}"
                class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus"></i>
                Create Appointment
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

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg" id="errorAlert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <p class="font-semibold text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Calendar Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Appointment Modal -->
    <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <form id="appointmentStatusForm" method="POST" action="{{ route('schedule-management.update.status') }}">
                @csrf
                <input type="hidden" name="appointment_id" id="modalAppointmentId">

                <!-- Modal Header -->
                <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">Appointment Details</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <strong class="text-gray-700">Assigned To:</strong>
                        <span id="modalEmployee" class="text-gray-900 ml-2">N/A</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Client:</strong>
                        <span id="modalAppointmentName" class="text-gray-900 ml-2">N/A</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Email:</strong>
                        <span id="modalEmail" class="text-gray-900 ml-2">N/A</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Phone:</strong>
                        <span id="modalPhone" class="text-gray-900 ml-2">N/A</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Date & Time:</strong>
                        <span id="modalStartTime" class="text-gray-900 ml-2">N/A</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Notes:</strong>
                        <p id="modalNotes" class="text-gray-900 mt-1">N/A</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Current Status:</strong>
                        <span id="modalStatusBadge" class="ml-2">N/A</span>
                    </div>

                    <div>
                        <label for="modalStatusSelect" class="block text-gray-700 font-semibold mb-2">Change Status:</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            id="modalStatusSelect">
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Close
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        onclick="return confirm('Are you sure you want to update the booking status?')">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" />
    <style>
        #calendar {
            background-color: white;
            border-radius: 8px;
        }

        .fc-toolbar h2 {
            font-size: 1.5em;
            color: #1f2937;
        }

        .fc-button {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            text-transform: capitalize !important;
            padding: 6px 12px !important;
            border-radius: 6px !important;
        }

        .fc-button:hover {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }

        .fc-state-active,
        .fc-state-active:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }

        .fc-today {
            background-color: #eff6ff !important;
        }

        /* Daily View Optimizations */
        .fc-agendaDay-view .fc-time-grid-container {
            height: auto !important;
        }

        .fc-agendaDay-view .fc-event {
            margin: 1px 2px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .fc-agendaDay-view .fc-event .fc-content {
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 2px 4px;
        }

        .fc-agendaDay-view .fc-time-grid {
            min-height: 600px !important;
        }

        .fc-agendaDay-view .fc-slats tr {
            height: 40px;
        }

        .fc-event {
            opacity: 0.9;
            transition: opacity 0.2s, transform 0.2s;
            cursor: pointer;
        }

        .fc-event:hover {
            opacity: 1;
            transform: scale(1.02);
            z-index: 1000 !important;
        }

        /* Month view improvements */
        .fc-day-grid-event {
            padding: 2px 4px;
            margin: 1px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>

    <script>
        // Auto hide alerts after 3 seconds
        setTimeout(function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');

            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }

            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.5s';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }
        }, 3000);

        // Modal functions
        function closeModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
            document.getElementById('appointmentModal').classList.remove('flex');
        }

        function openModal() {
            document.getElementById('appointmentModal').classList.remove('hidden');
            document.getElementById('appointmentModal').classList.add('flex');
        }

        // Close modal when clicking outside
        document.getElementById('appointmentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Initialize FullCalendar
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaDay'
                },
                defaultView: 'month',
                editable: false,
                slotDuration: '00:30:00',
                minTime: '06:00:00',
                maxTime: '22:00:00',
                height: 'auto',
                events: @json($appointments ?? []),
                eventRender: function(event, element) {
                    element.attr('title', event.description || 'No description');
                },
                eventClick: function(calEvent, jsEvent, view) {
                    // Populate modal with event data
                    $('#modalAppointmentId').val(calEvent.id);
                    $('#modalEmployee').text(calEvent.employee || 'Not assigned');
                    $('#modalAppointmentName').text(calEvent.name || 'N/A');
                    $('#modalEmail').text(calEvent.email || 'N/A');
                    $('#modalPhone').text(calEvent.phone || 'N/A');
                    $('#modalNotes').text(calEvent.description || calEvent.notes || 'N/A');
                    $('#modalStartTime').text(moment(calEvent.start).format('MMMM D, YYYY h:mm A'));

                    // Get the status from the calendar event
                    var status = calEvent.status || 'Pending';
                    $('#modalStatusSelect').val(status);

                    // Set status badge
                    var statusColors = {
                        'Pending': '#f39c12',
                        'Confirmed': '#2ecc71',
                        'Cancelled': '#ff0000',
                        'Completed': '#008000',
                    };

                    var badgeColor = statusColors[status] || '#7f8c8d';
                    $('#modalStatusBadge').html(
                        `<span class="px-3 py-1 rounded-full text-white font-semibold" style="background-color: ${badgeColor};">${status}</span>`
                    );

                    // Show modal
                    openModal();
                }
            });
        });
    </script>
@endpush
