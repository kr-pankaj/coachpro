<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Quiz::withCount('questions')->with('batch');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $quizzes = $query->latest()->paginate(12)->withQueryString();
        $batches = \App\Models\Batch::all();
        
        return view('quizzes.index', compact('quizzes', 'batches'));
    }

    public function create()
    {
        $batches = \App\Models\Batch::all();
        return view('quizzes.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'batch_id' => 'nullable|exists:batches,id',
            'time_limit_minutes' => 'required|integer|min:1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_active' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.correct_option' => 'required|integer',
        ]);

        $quiz = \App\Models\Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'batch_id' => $validated['batch_id'] ?? null,
            'time_limit_minutes' => $validated['time_limit_minutes'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        foreach ($request->questions as $i => $q) {
            $question = $quiz->questions()->create([
                'question_text' => $q['question_text'],
                'marks' => $q['marks'],
                'order' => $i,
            ]);
            $correctIndex = (int)$q['correct_option'];
            foreach ($q['options'] as $j => $opt) {
                $question->options()->create([
                    'option_text' => $opt['option_text'],
                    'is_correct' => ($j === $correctIndex),
                ]);
            }
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz created successfully!');
    }

    public function show(\App\Models\Quiz $quiz)
    {
        $quiz->load('questions.options', 'batch');
        $attempts = $quiz->attempts()->with('student')->latest()->get();
        return view('quizzes.show', compact('quiz', 'attempts'));
    }

    public function destroy(\App\Models\Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted.');
    }
}
