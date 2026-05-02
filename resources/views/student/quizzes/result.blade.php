<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Quiz Result</h2></x-slot>
    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            @php $pct = $attempt->total_marks > 0 ? round(($attempt->score / $attempt->total_marks) * 100) : 0; @endphp

            <!-- Score Card -->
            <div class="rounded-2xl p-8 text-center text-white" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                <p class="text-6xl font-black mb-1">{{ $pct }}%</p>
                <p class="text-lg font-semibold opacity-90">{{ $attempt->score }} out of {{ $attempt->total_marks }} marks</p>
                <p class="text-sm opacity-75 mt-2">{{ $attempt->quiz->title }}</p>
                <div class="mt-4">
                    @if($pct >= 75) <span class="bg-white/20 px-4 py-1.5 rounded-full text-sm font-bold">🏆 Excellent!</span>
                    @elseif($pct >= 50) <span class="bg-white/20 px-4 py-1.5 rounded-full text-sm font-bold">👍 Good Job!</span>
                    @else <span class="bg-white/20 px-4 py-1.5 rounded-full text-sm font-bold">📚 Keep Practicing!</span>
                    @endif
                </div>
            </div>

            <!-- Question Review -->
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Detailed Review</h3>
            @foreach($attempt->quiz->questions->sortBy('order') as $i => $question)
                @php
                    $myAnswer = $attempt->answers->where('question_id', $question->id)->first();
                    $isCorrect = $myAnswer?->selectedOption?->is_correct;
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border {{ $isCorrect ? 'border-green-200' : 'border-red-200' }} p-5">
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-white text-xs font-black mr-2 {{ $isCorrect ? 'bg-green-500' : 'bg-red-500' }}">{{ $i+1 }}</span>
                        {{ $question->question_text }}
                    </p>
                    <div class="space-y-1.5 ml-9">
                        @foreach($question->options as $option)
                            @php
                                $isMyChoice = $myAnswer?->quiz_option_id === $option->id;
                                $classes = $option->is_correct ? 'bg-green-50 border-green-400 text-green-800' : ($isMyChoice && !$option->is_correct ? 'bg-red-50 border-red-400 text-red-800' : 'border-gray-200 text-gray-600');
                            @endphp
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg border text-sm {{ $classes }}">
                                @if($option->is_correct) <span>✓</span>
                                @elseif($isMyChoice) <span>✗</span>
                                @else <span class="w-4"></span>
                                @endif
                                {{ $option->option_text }}
                                @if($option->is_correct) <span class="ml-auto text-xs font-bold">Correct Answer</span> @endif
                                @if($isMyChoice && !$option->is_correct) <span class="ml-auto text-xs font-bold">Your Answer</span> @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="text-center">
                <a href="{{ route('student.quizzes.index') }}" class="inline-block px-6 py-2.5 text-sm font-semibold text-indigo-600 border border-indigo-300 rounded-xl hover:bg-indigo-50 transition-colors">← Back to Tests</a>
            </div>
        </div>
    </div>
</x-app-layout>
