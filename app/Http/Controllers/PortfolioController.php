<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function show($slug, Student $student)
    {
        // Ensure student belongs to the institute
        if ($student->institute->slug !== $slug) {
            abort(404);
        }

        $student->load(['batches', 'badges', 'attempts.quiz']);

        // Stats
        $stats = [
            'quizzes_completed' => $student->attempts()->count(),
            'avg_score' => $student->attempts()->avg('score') ?? 0,
            'attendance_rate' => $student->attendances()->count() > 0 
                ? round(($student->attendances()->where('status', 'present')->count() / $student->attendances()->count()) * 100) 
                : 100,
            'rank' => Student::where('institute_id', $student->institute_id)
                ->orderByDesc('xp_total')
                ->get()
                ->search(fn($s) => $s->id == $student->id) + 1,
        ];

        // Skill Graph Data (Top subjects/categories from quizzes)
        $skills = $student->attempts()
            ->with('quiz')
            ->get()
            ->groupBy(fn($a) => $a->quiz->category ?? 'General')
            ->map(fn($group) => [
                'name' => $group->first()->quiz->category ?? 'General',
                'score' => round($group->avg(fn($a) => ($a->total_marks > 0) ? ($a->score / $a->total_marks) * 100 : 0))
            ])
            ->values()
            ->take(5);

        return view('student.portfolio.show', compact('student', 'stats', 'skills'));
    }
}
