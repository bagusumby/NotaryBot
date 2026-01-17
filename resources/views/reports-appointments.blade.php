@extends('layouts.dashboard')

@section('title', 'Appointment Reports')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-chart-bar w-6 h-6 text-gray-700"></i>
                <h1 class="text-2xl font-bold text-gray-800">Appointment Reports</h1>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <form action="{{ route('reports.appointments') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Total</span>
                    <i class="fas fa-calendar-check text-blue-600"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalAppointments }}</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Pending</span>
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="text-3xl font-bold text-yellow-600">{{ $pendingAppointments }}</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Confirmed</span>
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="text-3xl font-bold text-green-600">{{ $confirmedAppointments }}</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Completed</span>
                    <i class="fas fa-check-double text-blue-600"></i>
                </div>
                <div class="text-3xl font-bold text-blue-600">{{ $completedAppointments }}</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Cancelled</span>
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div class="text-3xl font-bold text-red-600">{{ $cancelledAppointments }}</div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Appointments by Day of Week -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-calendar-week mr-2 text-blue-600"></i>
                    Appointments by Day of Week
                </h2>
                <div style="height: 300px;">
                    <canvas id="dayChart"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Shows which day has the most appointments
                    </p>
                </div>
            </div>

            <!-- Appointments by Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                    Appointments by Status
                </h2>
                <div style="height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Trend & Top Employees -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Monthly Trend -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                    Monthly Trend (Last 6 Months)
                </h2>
                <div style="height: 250px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Top Employees -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-star mr-2 text-blue-600"></i>
                    Top Employees
                </h2>
                <div class="space-y-3">
                    @forelse($topEmployees as $index => $emp)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $emp->employee->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $emp->total }}</span>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Appointments by Day of Week Chart
            const dayCtx = document.getElementById('dayChart').getContext('2d');
            const dayChart = new Chart(dayCtx, {
                type: 'bar',
                data: {
                    labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    datasets: [{
                        label: 'Appointments',
                        data: [
                            {{ $daysData['Sunday'] }},
                            {{ $daysData['Monday'] }},
                            {{ $daysData['Tuesday'] }},
                            {{ $daysData['Wednesday'] }},
                            {{ $daysData['Thursday'] }},
                            {{ $daysData['Friday'] }},
                            {{ $daysData['Saturday'] }}
                        ],
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.7)', // Red - Sunday
                            'rgba(59, 130, 246, 0.7)', // Blue - Monday
                            'rgba(16, 185, 129, 0.7)', // Green - Tuesday
                            'rgba(245, 158, 11, 0.7)', // Orange - Wednesday
                            'rgba(139, 92, 246, 0.7)', // Purple - Thursday
                            'rgba(236, 72, 153, 0.7)', // Pink - Friday
                            'rgba(100, 116, 139, 0.7)' // Gray - Saturday
                        ],
                        borderColor: [
                            'rgba(239, 68, 68, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(236, 72, 153, 1)',
                            'rgba(100, 116, 139, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Appointments: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Appointments by Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $appointmentsByStatus['Pending'] ?? 0 }},
                            {{ $appointmentsByStatus['Confirmed'] ?? 0 }},
                            {{ $appointmentsByStatus['Completed'] ?? 0 }},
                            {{ $appointmentsByStatus['Cancelled'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.7)', // Yellow - Pending
                            'rgba(16, 185, 129, 0.7)', // Green - Confirmed
                            'rgba(59, 130, 246, 0.7)', // Blue - Completed
                            'rgba(239, 68, 68, 0.7)' // Red - Cancelled
                        ],
                        borderColor: [
                            'rgba(245, 158, 11, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Monthly Trend Chart
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach ($appointmentsByMonth as $month)
                            '{{ \Carbon\Carbon::parse($month->month . '-01')->format('M Y') }}'
                            {{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Appointments',
                        data: [
                            @foreach ($appointmentsByMonth as $month)
                                {{ $month->total }}{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        ],
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
