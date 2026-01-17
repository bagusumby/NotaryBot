@extends('layouts.dashboard')

@section('title', 'Schedule Management')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Schedule Management</h1>
                                <p class="text-sm text-gray-500 mt-0.5">Manage your appointments calendar</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('appointments.create') }}"
                        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                        <i class="fas fa-plus"></i>
                        <span>New Appointment</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 shadow-sm" id="successAlert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                        <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                        <button onclick="document.getElementById('successAlert').remove()" class="ml-auto text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 shadow-sm" id="errorAlert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                        </div>
                        <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                        <button onclick="document.getElementById('errorAlert').remove()" class="ml-auto text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Calendar Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Modal -->
    <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <form id="appointmentStatusForm" method="POST" action="{{ route('schedule-management.update.status') }}">
                @csrf
                <input type="hidden" name="appointment_id" id="modalAppointmentId">

                <!-- Modal Header -->
                <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <i class="fas fa-calendar-check"></i>
                        Appointment Details
                    </h3>
                    <button type="button" onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                    <!-- Client Information -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            Client Information
                        </h4>
                        <div class="space-y-2.5">
                            <div class="flex">
                                <span class="text-sm text-gray-600 w-28 flex-shrink-0">Name:</span>
                                <span id="modalAppointmentName" class="text-sm font-medium text-gray-900">N/A</span>
                            </div>
                            <div class="flex">
                                <span class="text-sm text-gray-600 w-28 flex-shrink-0">Email:</span>
                                <span id="modalEmail" class="text-sm text-gray-900 break-all">N/A</span>
                            </div>
                            <div class="flex">
                                <span class="text-sm text-gray-600 w-28 flex-shrink-0">Phone:</span>
                                <span id="modalPhone" class="text-sm text-gray-900">N/A</span>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-calendar text-blue-600"></i>
                            Appointment Details
                        </h4>
                        <div class="space-y-2.5">
                            <div class="flex">
                                <span class="text-sm text-gray-600 w-28 flex-shrink-0">Assigned to:</span>
                                <span id="modalEmployee" class="text-sm font-medium text-gray-900">N/A</span>
                            </div>
                            <div class="flex">
                                <span class="text-sm text-gray-600 w-28 flex-shrink-0">Date & Time:</span>
                                <span id="modalStartTime" class="text-sm font-medium text-gray-900">N/A</span>
                            </div>
                            <div class="flex">
                                <span class="text-sm text-gray-600 w-28 flex-shrink-0">Status:</span>
                                <span id="modalStatusBadge">N/A</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-amber-50 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-amber-600"></i>
                            Notes
                        </h4>
                        <p id="modalNotes" class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">N/A</p>
                    </div>

                    <!-- Update Status -->
                    <div class="border-t pt-4">
                        <label for="modalStatusSelect" class="block text-sm font-semibold text-gray-700 mb-2">
                            Update Status
                        </label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm"
                            id="modalStatusSelect">
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 border-t">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
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
        /* General Calendar Styling */
        #calendar {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Toolbar */
        .fc-toolbar {
            padding: 1rem;
            margin-bottom: 1.5rem !important;
            background: #f8fafc;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .fc-toolbar h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        /* Buttons */
        .fc-button {
            background: #3b82f6 !important;
            border: 1px solid #3b82f6 !important;
            color: white !important;
            text-transform: capitalize !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            transition: all 0.15s ease !important;
            text-align: center !important;
            line-height: 1.5 !important;
            vertical-align: middle !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .fc-button:hover {
            background: #2563eb !important;
            border-color: #2563eb !important;
        }

        .fc-button:active,
        .fc-state-active {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }

        .fc-button:disabled {
            opacity: 0.5 !important;
        }

        /* Day Headers */
        .fc-day-header {
            padding: 0.75rem !important;
            background: #f9fafb !important;
            font-weight: 600 !important;
            color: #374151 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border: 1px solid #e5e7eb !important;
        }

        /* Day Cells */
        .fc-day {
            border: 1px solid #e5e7eb !important;
        }

        .fc-day-number {
            padding: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .fc-today {
            background: #eff6ff !important;
        }

        .fc-today .fc-day-number {
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Events */
        .fc-event {
            border: none !important;
            border-radius: 0.25rem !important;
            padding: 2px 6px !important;
            font-size: 0.8125rem !important;
            cursor: pointer;
            transition: all 0.15s ease;
            margin: 1px 2px !important;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            opacity: 0.95;
        }

        .fc-event .fc-content {
            padding: 2px 4px;
        }

        .fc-time {
            font-weight: 600;
        }

        /* Day Grid */
        .fc-day-grid-event {
            margin: 2px 3px 0;
            padding: 3px 5px;
        }

        /* Time Grid (Agenda Day View) */
        .fc-time-grid .fc-slats td {
            height: 2.5rem;
            border-bottom: 1px solid #f3f4f6 !important;
        }

        .fc-time-grid .fc-slats .fc-minor td {
            border-top-style: dotted !important;
        }

        .fc-axis {
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .fc-time-grid-event {
            border-radius: 0.375rem !important;
            border-left-width: 3px !important;
        }

        /* Scrollbar */
        .fc-scroller::-webkit-scrollbar {
            width: 6px;
        }

        .fc-scroller::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .fc-scroller::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .fc-scroller::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Modal Animations */
        #appointmentModal.flex {
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        #appointmentModal .bg-white {
            animation: modalSlideUp 0.3s ease-out;
        }

        @keyframes modalSlideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>

    <script>
        // Auto hide alerts
        setTimeout(function() {
            ['successAlert', 'errorAlert'].forEach(id => {
                const alert = document.getElementById(id);
                if (alert) {
                    alert.style.transition = 'opacity 0.3s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 4000);

        // Modal functions
        function closeModal() {
            const modal = document.getElementById('appointmentModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openModal() {
            const modal = document.getElementById('appointmentModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Close modal on outside click
        document.getElementById('appointmentModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // FullCalendar initialization
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
                minTime: '07:00:00',
                maxTime: '20:00:00',
                height: 'auto',
                firstDay: 1, // Monday
                events: @json($appointments ?? []),
                
                eventRender: function(event, element) {
                    element.attr('title', event.description || event.notes || 'No description');
                    element.find('.fc-content').prepend('<i class="fas fa-circle" style="font-size: 6px; margin-right: 4px;"></i>');
                },
                
                eventClick: function(calEvent, jsEvent, view) {
                    // Populate modal
                    $('#modalAppointmentId').val(calEvent.id);
                    $('#modalEmployee').text(calEvent.employee || 'Not assigned');
                    $('#modalAppointmentName').text(calEvent.name || 'N/A');
                    $('#modalEmail').text(calEvent.email || 'N/A');
                    $('#modalPhone').text(calEvent.phone || 'N/A');
                    $('#modalNotes').text(calEvent.notes || calEvent.description || 'No notes provided');
                    $('#modalStartTime').text(moment(calEvent.start).format('dddd, MMMM D, YYYY [at] h:mm A'));

                    // Status
                    var status = calEvent.status || 'Pending';
                    $('#modalStatusSelect').val(status);

                    // Status badge with proper styling
                    var statusConfig = {
                        'Pending': { bg: '#fef3c7', text: '#92400e', label: 'Pending' },
                        'Confirmed': { bg: '#d1fae5', text: '#065f46', label: 'Confirmed' },
                        'Cancelled': { bg: '#fee2e2', text: '#991b1b', label: 'Cancelled' },
                        'Completed': { bg: '#dbeafe', text: '#1e40af', label: 'Completed' }
                    };

                    var config = statusConfig[status] || { bg: '#f3f4f6', text: '#374151', label: status };
                    $('#modalStatusBadge').html(
                        `<span class="status-badge" style="background-color: ${config.bg}; color: ${config.text};">${config.label}</span>`
                    );

                    openModal();
                },

                dayClick: function(date, jsEvent, view) {
                    // Optional: Add behavior when clicking on empty day
                }
            });
        });
    </script>
@endpush
