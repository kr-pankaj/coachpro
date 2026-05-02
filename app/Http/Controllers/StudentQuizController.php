<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentQuizController extends Controller
{
    public function index()
    {
        $student = \App\Models\Student::where('user_id', auth()->id())->firstOrFail();
        
        // Quizzes eligible for the student (Active or already attempted)
        $quizzes = \App\Models\Quiz::where(function($q) use ($student) {
                $q->where('is_active', true)
                  ->where(function ($sq) use ($student) {
                      $sq->whereNull('batch_id')->orWhere('batch_id', $student->batch_id);
                  });
            })
            ->orWhereHas('attempts', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->withCount(['attempts as my_attempts' => fn($q) => $q->where('student_id', $student->id)->whereNotNull('completed_at')])
            ->with(['attempts' => fn($q) => $q->where('student_id', $student->id)->whereNotNull('completed_at')])
            ->latest()
            ->get();
            
        return view('student.quizzes.index', compact('quizzes', 'student'));
    }

    public function take(\App\Models\Quiz $quiz)
    {
        $student = \App\Models\Student::where('user_id', auth()->id())->firstOrFail();
        $existingAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->whereNotNull('completed_at')->first();
        if ($existingAttempt) {
            return redirect()->route('student.quizzes.result', $existingAttempt)->with('info', 'You have already completed this quiz.');
        }
        // Create a new attempt or resume
        $attempt = \App\Models\QuizAttempt::firstOrCreate(
            ['quiz_id' => $quiz->id, 'student_id' => $student->id, 'completed_at' => null],
            ['started_at' => now()]
        );
        $quiz->load('questions.options');
        return view('student.quizzes.take', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, \App\Models\Quiz $quiz)
    {
        $student = \App\Models\Student::where('user_id', auth()->id())->firstOrFail();
        $attempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', $student->id)->whereNull('completed_at')->firstOrFail();

        $quiz->load('questions.options');
        $score = 0; $total = 0;
        foreach ($quiz->questions as $question) {
            $total += $question->marks;
            $selectedOptionId = $request->input("answers.{$question->id}");
            \App\Models\StudentAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'quiz_option_id' => $selectedOptionId ?: null,
            ]);
            if ($selectedOptionId && $question->options->where('id', $selectedOptionId)->first()?->is_correct) {
                $score += $question->marks;
            }
        }
        $attempt->update(['score' => $score, 'total_marks' => $total, 'completed_at' => now()]);
        return redirect()->route('student.quizzes.result', $attempt)->with('success', 'Quiz submitted!');
    }

    public function result(\App\Models\QuizAttempt $attempt)
    {
        $student = \App\Models\Student::where('user_id', auth()->id())->firstOrFail();
        if ($attempt->student_id !== $student->id) abort(403);
        $attempt->load('quiz.questions.options', 'answers.selectedOption');
        return view('student.quizzes.result', compact('attempt'));
    }
}
