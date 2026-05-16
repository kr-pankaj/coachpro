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
                'tests_taken' => QuizAttempt::selectRaw('count(*)')
                    ->whereColumn('student_id', 'students.id')
            ]);

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        $students = $query->orderByDesc('xp_total')
            ->orderByDesc('tests_taken')
            ->get();

        $batches = Batch::all();

        return view('leaderboard.index', compact('students', 'batches', 'batchId'));
    }
}
