<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">{{ $quiz->title }}</h2></x-slot>
    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Countdown Timer -->
            <div class="bg-indigo-600 rounded-2xl px-5 py-4 mb-6 flex items-center justify-between text-white">
                <span class="text-sm font-semibold">Time Remaining</span>
                <span id="timer" class="text-2xl font-black font-mono">{{ $quiz->time_limit_minutes }}:00</span>
            </div>

            <form method="POST" action="{{ route('student.quizzes.submit', $quiz) }}" id="quiz-form">
                @csrf
                <div class="space-y-5">
                    @foreach($quiz->questions->sortBy('order') as $i => $question)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-white text-xs font-black mr-2" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">{{ $i+1 }}</span>
                                {{ $question->question_text }}
                                <span class="ml-2 text-xs font-normal text-gray-400">({{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }})</span>
                            </p>
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="text-sm text-gray-800 dark:text-gray-200">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" onclick="return confirm('Are you sure you want to submit? You cannot change answers after submission.')" class="px-8 py-3 text-sm font-bold text-white rounded-xl shadow-lg" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        Submit Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function() {
        const totalSeconds = {{ $quiz->time_limit_minutes * 60 }};
        const startKey = 'quiz_start_{{ $attempt->id }}';
        let startTime = localStorage.getItem(startKey);
        if (!startTime) { startTime = Date.now(); localStorage.setItem(startKey, startTime); }
        else { startTime = parseInt(startTime); }

        const display = document.getElementById('timer');
        function update() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const remaining = Math.max(0, totalSeconds - elapsed);
            const m = String(Math.floor(remaining / 60)).padStart(2, '0');
            const s = String(remaining % 60).padStart(2, '0');
            display.textContent = m + ':' + s;
            if (remaining <= 60) display.closest('div').style.background = '#dc2626';
            if (remaining <= 0) {
                localStorage.removeItem(startKey);
                document.getElementById('quiz-form').submit();
            }
        }
        update();
        setInterval(update, 1000);
    })();
    </script>
</x-app-layout>
