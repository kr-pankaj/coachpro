<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\QuizAttempt;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $batchId = $request->batch_id;
        
        $query = Student::query()
            ->with('batch')
            ->select('students.*')
            ->addSelect([
                'total_score' => QuizAttempt::selectRaw('sum(score)')
                    ->whereColumn('student_id', 'students.id'),
                'total_possible' => QuizAttempt::selectRaw('sum(total_marks)')
                    ->whereColumn('student_id', 'students.id'),
                'tests_taken' => QuizAttempt::selectRaw('count(*)')
                    ->whereColumn('student_id', 'students.id')
            ]);

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        $students = $query->get()
            ->filter(fn($s) => $s->tests_taken > 0)
            ->map(function($s) {
                $s->average_percentage = $s->total_possible > 0 ? round(($s->total_score / $s->total_possible) * 100, 1) : 0;
                return $s;
            })
            ->sortByDesc('average_percentage')
            ->values();

        $batches = Batch::all();

        return view('leaderboard.index', compact('students', 'batches', 'batchId'));
    }
}
