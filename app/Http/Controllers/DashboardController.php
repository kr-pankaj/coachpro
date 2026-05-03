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
            if (!$student) return view('student.dashboard');
            
            $fees        = Fee::where('student_id', $student->id)->orderBy('month_year', 'desc')->get();
            $attendances = Attendance::where('student_id', $student->id)->orderBy('date', 'desc')->take(30)->get();
            
            // Student Performance
            $attempts = \App\Models\QuizAttempt::where('student_id', $student->id)->with('quiz')->latest()->take(5)->get();
            $avgScore = \App\Models\QuizAttempt::where('student_id', $student->id)->avg('score');
            $totalPossible = \App\Models\QuizAttempt::where('student_id', $student->id)->avg('total_marks');
            $performanceRate = $totalPossible > 0 ? round(($avgScore / $totalPossible) * 100) : 0;

            // Attendance Rate
            $presentCount = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
            $totalCount = Attendance::where('student_id', $student->id)->count();
            $attendanceRate = $totalCount > 0 ? round(($presentCount / $totalCount) * 100) : 0;

            // Upcoming Quizzes for their batch
            $upcomingQuizzes = \App\Models\Quiz::where('batch_id', $student->batch_id)
                ->where('is_active', true)
                ->whereDoesntHave('attempts', fn($q) => $q->where('student_id', $student->id))
                ->latest()->take(3)->get();

            // Digital Badges
            $badges = [];
            if ($performanceRate >= 90) $badges[] = ['label' => 'Elite Performer', 'icon' => '⭐', 'color' => 'amber'];
            if ($attendanceRate >= 95) $badges[] = ['label' => 'Perfect Attendance', 'icon' => '🎯', 'color' => 'emerald'];
            if ($student->attempts()->count() >= 10) $badges[] = ['label' => 'Dedicated Learner', 'icon' => '📚', 'color' => 'indigo'];
            
            $rank = Student::query()
                ->select('students.*')
                ->addSelect([
                    'avg_pct' => \App\Models\QuizAttempt::selectRaw('avg(score/total_marks)*100')
                        ->whereColumn('student_id', 'students.id')
                ])
                ->orderByDesc('avg_pct')
                ->get()
                ->search(fn($s) => $s->id == $student->id);
            
            if ($rank !== false && $rank < 3) $badges[] = ['label' => 'Top 3 Global', 'icon' => '🏆', 'color' => 'rose'];

            // Learning Streak
            $streak = 0;
            $activityDates = array_merge(
                $attendances->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())->toArray(),
                $student->attempts()->pluck('completed_at')->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())->toArray()
            );
            $activityDates = array_unique($activityDates);
            rsort($activityDates);
            
            if (!empty($activityDates)) {
                $currentDate = \Carbon\Carbon::parse($activityDates[0]);
                if ($currentDate->isToday() || $currentDate->isYesterday()) {
                    $streak = 1;
                    for ($i = 1; $i < count($activityDates); $i++) {
                        $prevDate = \Carbon\Carbon::parse($activityDates[$i]);
                        if ($currentDate->diffInDays($prevDate) == 1) {
                            $streak++;
                            $currentDate = $prevDate;
                        } else {
                            break;
                        }
                    }
                }
            }

            return view('student.dashboard', compact(
                'student', 'fees', 'attendances', 
                'attempts', 'performanceRate', 'attendanceRate', 'upcomingQuizzes', 'badges', 'streak'
            ));
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

        // AI Retention Analysis (At-Risk Students)
        $atRiskStudents = Student::with(['batch', 'attendances', 'attempts'])
            ->get()
            ->map(function($student) {
                $riskReasons = [];
                
                // 1. Attendance Check (Last 10 records)
                $recentAttendance = $student->attendances()->latest()->take(10)->get();
                $absentCount = $recentAttendance->where('status', 'absent')->count();
                if ($absentCount >= 3) {
                    $riskReasons[] = "Low Attendance ({$absentCount}/10 absent)";
                }

                // 2. Performance Check (Last 5 attempts)
                $recentAttempts = $student->attempts()->latest()->take(5)->get();
                if ($recentAttempts->count() > 0) {
                    $avgScore = $recentAttempts->avg(function($a) {
                        return ($a->total_marks > 0) ? ($a->score / $a->total_marks) * 100 : 0;
                    });
                    
                    if ($avgScore < 40) {
                        $riskReasons[] = "Poor Academic Performance (" . round($avgScore) . "%)";
                    }

                    // 3. Trend Check (Declining scores)
                    if ($recentAttempts->count() >= 3) {
                        $scores = $recentAttempts->map(function($a) {
                            return ($a->total_marks > 0) ? $a->score / $a->total_marks : 0;
                        })->toArray();
                        
                        if ($scores[0] < $scores[1] && $scores[1] < $scores[2]) {
                            $riskReasons[] = "Declining Test Scores";
                        }
                    }
                }

                $student->risk_reasons = $riskReasons;
                $student->risk_level = count($riskReasons);
                return $student;
            })
            ->filter(fn($s) => $s->risk_level > 0)
            ->sortByDesc('risk_level')
            ->take(5);

        return view('dashboard', compact(
            'institute', 'totalStudents', 'totalBatches',
            'todayAttended', 'todayTotal', 'pendingFees', 'collectedFees',
            'recentStudents', 'batches', 'announcements', 'profilePct',
            'chartData', 'revenueData', 'batchPerformance', 'conversionRate', 'leadsToday',
            'atRiskStudents'
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
