<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Batch;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Announcement;
use App\Models\AddOn;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
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
            
            // Announcements
            $announcements = Announcement::where('institute_id', $student->institute_id)
                ->where(fn($q) => $q->whereNull('expires_on')->orWhere('expires_on', '>=', today()))
                ->orderByDesc('created_at')->take(5)->get();

            return view('student.dashboard', compact(
                'student', 'fees', 'attendances', 
                'attempts', 'performanceRate', 'attendanceRate', 'upcomingQuizzes', 'badges', 'streak',
                'announcements'
            ));
        }

        // ─── ADMIN DASHBOARD ───────────────────────────────────────────────────────
        $institute   = auth()->user()->institute;
        $instituteId = $institute->id;

        // Date-range filter params from request
        $enrollmentRange = (int) $request->get('enrollment_range', 30); // default 30 days
        $revenueRange    = (int) $request->get('revenue_range', 6);     // default 6 months
        // Clamp to allowed values
        $enrollmentRange = in_array($enrollmentRange, [7, 30, 90]) ? $enrollmentRange : 30;
        $revenueRange    = in_array($revenueRange, [3, 6, 12]) ? $revenueRange : 6;

        // ── Scoped KPI Stats ───────────────────────────────────────────────────────
        $totalStudents = Student::where('institute_id', $instituteId)->count();
        $totalBatches  = Batch::where('institute_id', $instituteId)->count();

        $todayAttended = Attendance::where('institute_id', $instituteId)->whereDate('date', today())->where('status', 'present')->count();
        $todayTotal    = Attendance::where('institute_id', $instituteId)->whereDate('date', today())->count();

        $pendingFees   = Fee::where('institute_id', $instituteId)->sum('due_amount');
        $collectedFees = Fee::where('institute_id', $instituteId)->sum('paid_amount');

        // ── Today's Activity Stats ─────────────────────────────────────────────────
        $todayFees        = Fee::where('institute_id', $instituteId)->whereDate('payment_date', today())->sum('paid_amount');
        $todayEnrollments = Student::where('institute_id', $instituteId)->whereDate('created_at', today())->count();
        $todayLeads       = Enquiry::where('institute_id', $instituteId)->whereDate('created_at', today())->count();

        // ── Leads Stats ────────────────────────────────────────────────────────────
        $totalLeads     = Enquiry::where('institute_id', $instituteId)->count();
        $leadsToday     = $todayLeads;
        $convertedLeads = Enquiry::where('institute_id', $instituteId)->where('status', 'converted')->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100) : 0;

        // Leads funnel for pipeline display
        $leadsFunnel = [
            'new'            => Enquiry::where('institute_id', $instituteId)->where('status', 'new')->count(),
            'contacted'      => Enquiry::where('institute_id', $instituteId)->where('status', 'contacted')->count(),
            'demo_scheduled' => Enquiry::where('institute_id', $instituteId)->where('status', 'demo_scheduled')->count(),
            'converted'      => $convertedLeads,
            'lost'           => Enquiry::where('institute_id', $instituteId)->where('status', 'lost')->count(),
        ];

        // ── Supporting Lists ───────────────────────────────────────────────────────
        $recentStudents = Student::where('institute_id', $instituteId)->with('batch')->latest()->take(5)->get();
        $batches        = Batch::where('institute_id', $instituteId)->withCount('students')->get();

        $announcements = Announcement::where('institute_id', $instituteId)
            ->where(fn($q) => $q->whereNull('expires_on')->orWhere('expires_on', '>=', today()))
            ->orderByDesc('created_at')->take(5)->get();

        $profilePct = $institute->profileCompletion();

        // ── Chart Data ─────────────────────────────────────────────────────────────
        $chartData   = $this->getEnrollmentData($instituteId, $enrollmentRange);
        $revenueData = $this->getRevenueData($instituteId, $revenueRange);

        // ── Batch Attendance Performance ───────────────────────────────────────────
        $batchPerformance = Batch::where('institute_id', $instituteId)
            ->withCount(['students', 'attendances' => function($q) {
                $q->where('status', 'present');
            }])->get()->map(function($batch) {
                $totalPossible = $batch->students_count * Attendance::where('batch_id', $batch->id)->distinct('date')->count();
                $batch->attendance_rate = $totalPossible > 0 ? round(($batch->attendances_count / $totalPossible) * 100) : 0;
                return $batch;
            });

        // ── AI At-Risk Students ────────────────────────────────────────────────────
        $atRiskStudents = Student::where('institute_id', $instituteId)
            ->with(['batch', 'attendances', 'attempts'])
            ->get()
            ->map(function($student) {
                $riskReasons = [];
                
                $recentAttendance = $student->attendances()->latest()->take(10)->get();
                $absentCount = $recentAttendance->where('status', 'absent')->count();
                if ($absentCount >= 3) {
                    $riskReasons[] = "Low Attendance ({$absentCount}/10 absent)";
                }

                $recentAttempts = $student->attempts()->latest()->take(5)->get();
                if ($recentAttempts->count() > 0) {
                    $avgScore = $recentAttempts->avg(function($a) {
                        return ($a->total_marks > 0) ? ($a->score / $a->total_marks) * 100 : 0;
                    });
                    
                    if ($avgScore < 40) {
                        $riskReasons[] = "Poor Academic Performance (" . round($avgScore) . "%)";
                    }

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
                $student->risk_level   = count($riskReasons);
                return $student;
            })
            ->filter(fn($s) => $s->risk_level > 0)
            ->sortByDesc('risk_level')
            ->take(5);

        return view('dashboard', compact(
            'institute', 'totalStudents', 'totalBatches',
            'todayAttended', 'todayTotal', 'pendingFees', 'collectedFees',
            'todayFees', 'todayEnrollments', 'todayLeads',
            'recentStudents', 'batches', 'announcements', 'profilePct',
            'chartData', 'revenueData', 'batchPerformance', 'conversionRate', 'leadsToday',
            'atRiskStudents',
            'enrollmentRange', 'revenueRange',
            'leadsFunnel', 'totalLeads', 'convertedLeads'
        ));
    }

    private function getEnrollmentData(int $instituteId, int $days = 30)
    {
        $labels = [];
        $counts = [];

        $enrollments = Student::where('institute_id', $instituteId)
            ->where('created_at', '>=', today()->subDays($days - 1)->startOfDay())
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        // Build a complete array for every day in the range
        for ($i = $days - 1; $i >= 0; $i--) {
            $date    = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();

            // Label: show fewer ticks for 90-day range to avoid crowding
            if ($days <= 7) {
                $labels[] = $date->format('D');
            } elseif ($days <= 30) {
                $labels[] = $date->format('d M');
            } else {
                // For 90 days, label every 7th day to avoid crowding
                $labels[] = ($i % 7 === 0 || $i === $days - 1) ? $date->format('d M') : '';
            }

            $counts[] = (int) ($enrollments[$dateStr] ?? 0);
        }

        return ['days' => $labels, 'counts' => $counts];
    }

    private function getRevenueData(int $instituteId, int $months = 6)
    {
        $monthLabels = [];
        $amounts     = [];

        $revenues = Fee::where('institute_id', $instituteId)
            ->where('paid_amount', '>', 0)
            ->where('created_at', '>=', today()->subMonths($months - 1)->startOfMonth())
            ->selectRaw("month_year, sum(paid_amount) as total")
            ->groupBy('month_year')
            ->get()
            ->pluck('total', 'month_year');

        for ($i = $months - 1; $i >= 0; $i--) {
            $date      = Carbon::today()->subMonths($i);
            $monthYear = $date->format('F Y');
            $monthLabels[] = $date->format('M \'y');
            $amounts[]     = (float) ($revenues[$monthYear] ?? 0);
        }

        return ['months' => $monthLabels, 'amounts' => $amounts];
    }
}
