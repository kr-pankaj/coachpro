<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Batch;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'superadmin') {
            return redirect()->route('superadmin.index');
        }

        if (auth()->user()->role === 'student') {
            $student = Student::where('user_id', auth()->id())->first();
            if (!$student) return view('dashboard');
            $fees        = Fee::where('student_id', $student->id)->orderBy('month_year', 'desc')->get();
            $attendances = Attendance::where('student_id', $student->id)->orderBy('date', 'desc')->take(30)->get();
            return view('student.dashboard', compact('student', 'fees', 'attendances'));
        }

        // Admin dashboard
        $institute    = auth()->user()->institute;
        $totalStudents = Student::count();
        $totalBatches  = Batch::count();

        $todayAttended = Attendance::whereDate('date', today())->where('status', 'present')->count();
        $todayTotal    = Attendance::whereDate('date', today())->count();

        $pendingFees   = Fee::sum('due_amount');
        $collectedFees = Fee::sum('paid_amount');

        $recentStudents = Student::with('batch')->latest()->take(5)->get();
        $batches        = Batch::withCount('students')->get();

        $announcements = Announcement::where('institute_id', $institute->id)
            ->where(fn($q) => $q->whereNull('expires_on')->orWhere('expires_on', '>=', today()))
            ->orderByDesc('created_at')->take(5)->get();

        $profilePct    = $institute->profileCompletion();

        // Chart Data: Real Enrollment Growth (Last 7 Days)
        $chartData = $this->getEnrollmentData();

        // Revenue Trends (Last 6 Months)
        $revenueData = $this->getRevenueData();

        // Attendance by Batch
        $batchPerformance = Batch::withCount(['students', 'attendances' => function($q) {
            $q->where('status', 'present');
        }])->get()->map(function($batch) {
            $totalPossible = $batch->students_count * Attendance::where('batch_id', $batch->id)->distinct('date')->count();
            $batch->attendance_rate = $totalPossible > 0 ? round(($batch->attendances_count / $totalPossible) * 100) : 0;
            return $batch;
        });

        // Lead conversion & Today's Leads
        $totalLeads = \App\Models\Enquiry::count();
        $leadsToday = \App\Models\Enquiry::whereDate('created_at', today())->count();
        $convertedLeads = \App\Models\Enquiry::where('status', 'converted')->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100) : 0;

        return view('dashboard', compact(
            'institute', 'totalStudents', 'totalBatches',
            'todayAttended', 'todayTotal', 'pendingFees', 'collectedFees',
            'recentStudents', 'batches', 'announcements', 'profilePct',
            'chartData', 'revenueData', 'batchPerformance', 'conversionRate', 'leadsToday'
        ));
    }

    private function getEnrollmentData()
    {
        $days = [];
        $counts = [];
        
        $enrollments = Student::where('created_at', '>=', today()->subDays(6))
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            $days[] = $date->format('D');
            $counts[] = $enrollments[$dateStr] ?? 0;
        }
        return ['days' => $days, 'counts' => $counts];
    }

    private function getRevenueData()
    {
        $months = [];
        $amounts = [];

        $revenues = Fee::where('paid_amount', '>', 0)
            ->where('created_at', '>=', today()->subMonths(5)->startOfMonth())
            ->selectRaw("month_year, sum(paid_amount) as total")
            ->groupBy('month_year')
            ->get()
            ->pluck('total', 'month_year');

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::today()->subMonths($i);
            $monthYear = $date->format('F Y');
            $months[] = $date->format('M');
            $amounts[] = (float) ($revenues[$monthYear] ?? 0);
        }
        return ['months' => $months, 'amounts' => $amounts];
    }
}
