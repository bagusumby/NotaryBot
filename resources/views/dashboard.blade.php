@extends('layouts.dashboard')

@section('title', 'Dashboard - Notary Services')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl text-gray-900 mb-2">Dashboard</h1>
            <p class="text-gray-600">Welcome back! Here's your bot analytics overview.</p>
        </div>

        <!-- Top Stats - 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Bot Users -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-users w-6 h-6 text-blue-600"></i>
                    </div>
                    <div class="flex items-center gap-1 {{ $userGrowth >= 0 ? 'text-blue-600' : 'text-red-600' }} text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4 {{ $userGrowth < 0 ? 'rotate-180' : '' }}"></i>
                        <span>{{ $userGrowth >= 0 ? '+' : '' }}{{ number_format($userGrowth, 1) }}%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">{{ number_format($totalUsers) }}</p>
                <p class="text-gray-600">Total Bot Users</p>
                <p class="text-sm text-gray-500 mt-2">+{{ number_format($usersThisMonth) }} this month</p>
            </div>

            <!-- Positive Reviews -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-thumbs-up w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex items-center gap-1 {{ $positiveGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4 {{ $positiveGrowth < 0 ? 'rotate-180' : '' }}"></i>
                        <span>{{ $positiveGrowth >= 0 ? '+' : '' }}{{ number_format($positiveGrowth, 1) }}%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">{{ number_format($positiveReviews) }}</p>
                <p class="text-gray-600">Positive Reviews</p>
                <p class="text-sm text-gray-500 mt-2">{{ number_format($satisfactionRate, 1) }}% satisfaction rate</p>
            </div>

            <!-- Negative Reviews -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-red-50 p-3 rounded-lg">
                        <i class="fas fa-thumbs-down w-6 h-6 text-red-600"></i>
                    </div>
                    <div class="flex items-center gap-1 {{ $negativeGrowth <= 0 ? 'text-green-600' : 'text-red-600' }} text-sm">
                        <i class="fas fa-arrow-trend-up w-4 h-4 {{ $negativeGrowth <= 0 ? 'rotate-180' : '' }}"></i>
                        <span>{{ $negativeGrowth <= 0 ? '' : '+' }}{{ number_format($negativeGrowth, 1) }}%</span>
                    </div>
                </div>
                <p class="text-3xl text-gray-900 mb-1">{{ number_format($negativeReviews) }}</p>
                <p class="text-gray-600">Negative Reviews</p>
                <p class="text-sm text-gray-500 mt-2">{{ number_format($improvementRate, 1) }}% needs improvement</p>
            </div>
        </div>

        <!-- Secondary Stats - 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Conversations -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <i class="fas fa-comment w-6 h-6 text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($totalConversations) }}</p>
                        <p class="text-gray-600">Total Conversations</p>
                        <p class="text-sm text-gray-500 mt-1">Average {{ number_format($avgConversationsPerDay) }}/day</p>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="bg-orange-50 p-3 rounded-lg">
                        <i class="fas fa-chart-bar w-6 h-6 text-orange-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($successRate, 1) }}%</p>
                        <p class="text-gray-600">Success Rate</p>
                        <p class="text-sm text-gray-500 mt-1">Bot resolved queries</p>
                    </div>
                </div>
            </div>

            <!-- Total Intents -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="bg-cyan-50 p-3 rounded-lg">
                        <i class="fas fa-brain w-6 h-6 text-cyan-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($totalIntents) }}</p>
                        <p class="text-gray-600">Total Intents</p>
                        <p class="text-sm text-gray-500 mt-1">Bot training data</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unanswered Questions Stats - 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Unanswered Questions -->
            <a href="{{ route('unanswered-questions') }}" class="bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-xl shadow-sm border border-yellow-200 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-question-circle w-6 h-6 text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($totalUnansweredQuestions) }}</p>
                        <p class="text-gray-600 font-medium">Total Questions</p>
                        <p class="text-sm text-gray-500 mt-1">Click to view all</p>
                    </div>
                </div>
            </a>

            <!-- Unsolved Questions -->
            <a href="{{ route('unanswered-questions') }}" class="bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-xl shadow-sm border border-red-200 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-exclamation-triangle w-6 h-6 text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($unsolvedQuestions) }}</p>
                        <p class="text-gray-600 font-medium">Unsolved Questions</p>
                        <p class="text-sm text-gray-500 mt-1">Needs attention</p>
                    </div>
                </div>
            </a>

            <!-- Solved Questions -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl shadow-sm border border-green-200">
                <div class="flex items-start gap-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check-circle w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-2xl text-gray-900 mb-1">{{ number_format($solvedQuestions) }}</p>
                        <p class="text-gray-600 font-medium">Solved Questions</p>
                        <p class="text-sm text-gray-500 mt-1">+{{ number_format($solvedQuestionsThisMonth) }} this month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('reports') }}"
                    class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-file-alt w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-900">View Reports</p>
                        <p class="text-sm text-gray-500">Check detailed analytics</p>
                    </div>
                </a>

                <a href="{{ route('bot-training') }}"
                    class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-robot w-6 h-6 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-900">Train Bot</p>
                        <p class="text-sm text-gray-500">Add new responses</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Appointments by Day of Week -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Appointments by Day</h2>
                        <p class="text-sm text-gray-500 mt-1">Most popular booking days</p>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <i class="fas fa-calendar-day text-blue-600"></i>
                    </div>
                </div>
                <div class="h-56">
                    <canvas id="appointmentsByDayChart"></canvas>
                </div>
            </div>

            <!-- Appointment Status Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Appointment Status</h2>
                        <p class="text-sm text-gray-500 mt-1">Status distribution overview</p>
                    </div>
                    <div class="bg-purple-50 p-2 rounded-lg">
                        <i class="fas fa-chart-pie text-purple-600"></i>
                    </div>
                </div>
                <div class="h-56">
                    <canvas id="appointmentStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Growth and Trends Charts -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <!-- User Growth Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">User Growth Trend</h2>
                        <p class="text-sm text-gray-500 mt-1">New users over the last 6 months</p>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <i class="fas fa-chart-line text-blue-600"></i>
                    </div>
                </div>
                <div class="h-56">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            <!-- Reviews Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Reviews Trend</h2>
                        <p class="text-sm text-gray-500 mt-1">Positive vs negative reviews comparison</p>
                    </div>
                    <div class="bg-green-50 p-2 rounded-lg">
                        <i class="fas fa-thumbs-up text-green-600"></i>
                    </div>
                </div>
                <div class="h-56">
                    <canvas id="reviewsTrendChart"></canvas>
                </div>
            </div>

            <!-- Unanswered Questions Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Questions Performance</h2>
                        <p class="text-sm text-gray-500 mt-1">Solved vs unsolved questions tracking</p>
                    </div>
                    <div class="bg-yellow-50 p-2 rounded-lg">
                        <i class="fas fa-question-circle text-yellow-600"></i>
                    </div>
                </div>
                <div class="h-56">
                    <canvas id="unansweredQuestionsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl text-gray-900 mb-4">Recent Users</h2>
                @if($recentUsers->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-gray-900 font-medium">{{ $user->name ?? 'Anonymous' }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email ?? 'No email' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No recent users</p>
                @endif
            </div>

            <!-- Recent Reviews -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl text-gray-900 mb-4">Recent Reviews</h2>
                @if($recentReviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentReviews as $review)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    @if($review->rating === 'positive')
                                        <i class="fas fa-thumbs-up text-green-600"></i>
                                    @else
                                        <i class="fas fa-thumbs-down text-red-600"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-900 font-medium text-sm">{{ $review->chatUser->name ?? 'Anonymous' }}</p>
                                    @if($review->comment)
                                        <p class="text-sm text-gray-600 truncate">{{ $review->comment }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No recent reviews</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Chart Colors
        const colors = {
            blue: 'rgb(59, 130, 246)',
            green: 'rgb(34, 197, 94)',
            red: 'rgb(239, 68, 68)',
            yellow: 'rgb(234, 179, 8)',
            purple: 'rgb(168, 85, 247)',
            orange: 'rgb(249, 115, 22)',
            cyan: 'rgb(6, 182, 212)',
        };

        // 1. Appointments by Day of Week Chart
        const appointmentsByDayCtx = document.getElementById('appointmentsByDayChart').getContext('2d');
        new Chart(appointmentsByDayCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($appointmentsByDayFormatted)) !!},
                datasets: [{
                    label: 'Appointments',
                    data: {!! json_encode(array_values($appointmentsByDayFormatted)) !!},
                    backgroundColor: colors.blue,
                    borderRadius: 8,
                    barThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Appointment Status Distribution Chart
        const appointmentStatusCtx = document.getElementById('appointmentStatusChart').getContext('2d');
        const statusData = {!! json_encode($appointmentStatusChart) !!};
        new Chart(appointmentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        colors.green,
                        colors.yellow,
                        colors.blue,
                        colors.red,
                        colors.purple
                    ],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 13
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                }
            }
        });

        // 3. User Growth Chart
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($userGrowthChart)) !!},
                datasets: [{
                    label: 'New Users',
                    data: {!! json_encode(array_values($userGrowthChart)) !!},
                    borderColor: colors.blue,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: colors.blue,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 4. Reviews Trend Chart
        const reviewsTrendCtx = document.getElementById('reviewsTrendChart').getContext('2d');
        new Chart(reviewsTrendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($reviewsTrendChart['months']) !!},
                datasets: [
                    {
                        label: 'Positive Reviews',
                        data: {!! json_encode($reviewsTrendChart['positive']) !!},
                        borderColor: colors.green,
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: colors.green,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    },
                    {
                        label: 'Negative Reviews',
                        data: {!! json_encode($reviewsTrendChart['negative']) !!},
                        borderColor: colors.red,
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: colors.red,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 5. Unanswered Questions Performance Chart
        const unansweredQuestionsCtx = document.getElementById('unansweredQuestionsChart').getContext('2d');
        new Chart(unansweredQuestionsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($unansweredQuestionsChart['months']) !!},
                datasets: [
                    {
                        label: 'Solved',
                        data: {!! json_encode($unansweredQuestionsChart['solved']) !!},
                        backgroundColor: colors.green,
                        borderRadius: 6,
                    },
                    {
                        label: 'Unsolved',
                        data: {!! json_encode($unansweredQuestionsChart['unsolved']) !!},
                        backgroundColor: colors.red,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
