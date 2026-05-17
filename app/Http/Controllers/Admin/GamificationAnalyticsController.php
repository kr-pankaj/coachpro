<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ExperienceTransaction;
use App\Models\Badge;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GamificationAnalyticsController extends Controller
{
    public function index()
    {
        $instituteId = request()->get('resolved_institute')->id;

        $stats = [
            'total_xp' => Student::where('institute_id', $instituteId)->sum('xp_total'),
            'avg_level' => round(Student::where('institute_id', $instituteId)->avg('level'), 1),
            'active_streaks' => Student::where('institute_id', $instituteId)->where('current_streak', '>', 0)->count(),
            'badges_awarded' => DB::table('badge_student')
                ->join('students', 'badge_student.student_id', '=', 'students.id')
                ->where('students.institute_id', $instituteId)
                ->count(),
        ];

        // XP Distribution over last 30 days
        $xpChart = ExperienceTransaction::whereHas('student', fn($q) => $q->where('institute_id', $instituteId))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Students this week
        $topStudents = Student::where('institute_id', $instituteId)
            ->orderByDesc('xp_total')
            ->take(5)
            ->get();

        return view('admin.gamification.index', compact('stats', 'xpChart', 'topStudents'));
    }
}
