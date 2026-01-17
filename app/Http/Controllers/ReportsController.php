<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intent;
use App\Models\ChatUser;
use App\Models\Review;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        // Get all intents with usage count > 0, ordered by usage
        $intents = Intent::where('usage_count', '>', 0)
            ->orderBy('usage_count', 'desc')
            ->get();
        
        // Calculate statistics
        $totalIntentUsage = Intent::sum('usage_count');
        $totalUsers = ChatUser::count();
        $totalReviews = Review::count();
        $positiveReviews = Review::where('rating', 'positive')->count();
        $negativeReviews = Review::where('rating', 'negative')->count();
        
        // Calculate percentages for each intent
        $intents = $intents->map(function($intent) use ($totalIntentUsage) {
            $intent->percentage = $totalIntentUsage > 0 
                ? round(($intent->usage_count / $totalIntentUsage) * 100, 2) 
                : 0;
            return $intent;
        });
        
        return view('reports', compact(
            'intents',
            'totalIntentUsage',
            'totalUsers',
            'totalReviews',
            'positiveReviews',
            'negativeReviews'
        ));
    }

    public function appointments(Request $request)
    {
        // Get date range from request, default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Get appointments by day of week
        $appointmentsByDay = Appointment::whereBetween('booking_date', [$startDate, $endDate])
            ->select(
                DB::raw('DAYNAME(booking_date) as day_name'),
                DB::raw('DAYOFWEEK(booking_date) as day_number'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('day_name', 'day_number')
            ->orderBy('day_number')
            ->get();

        // Initialize all days with 0
        $daysData = [
            'Sunday' => 0,
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0
        ];

        // Fill with actual data
        foreach ($appointmentsByDay as $day) {
            $daysData[$day->day_name] = $day->total;
        }

        // Get appointments by status
        $appointmentsByStatus = Appointment::whereBetween('booking_date', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Get appointments by month (last 6 months)
        $appointmentsByMonth = Appointment::where('booking_date', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(booking_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get top employees by appointment count
        $topEmployees = Appointment::whereBetween('booking_date', [$startDate, $endDate])
            ->select('employee_id', DB::raw('COUNT(*) as total'))
            ->with('employee:id,name')
            ->groupBy('employee_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Statistics
        $totalAppointments = Appointment::whereBetween('booking_date', [$startDate, $endDate])->count();
        $pendingAppointments = Appointment::whereBetween('booking_date', [$startDate, $endDate])
            ->where('status', 'Pending')->count();
        $confirmedAppointments = Appointment::whereBetween('booking_date', [$startDate, $endDate])
            ->where('status', 'Confirmed')->count();
        $completedAppointments = Appointment::whereBetween('booking_date', [$startDate, $endDate])
            ->where('status', 'Completed')->count();
        $cancelledAppointments = Appointment::whereBetween('booking_date', [$startDate, $endDate])
            ->where('status', 'Cancelled')->count();

        return view('reports-appointments', compact(
            'daysData',
            'appointmentsByStatus',
            'appointmentsByMonth',
            'topEmployees',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'startDate',
            'endDate'
        ));
    }
}
