<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Available Tests</h2></x-slot>
    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            @forelse($quizzes as $quiz)
                @php $done = $quiz->my_attempts > 0; $score = $quiz->attempts->first(); @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ $quiz->batch?->name ?? 'All Students' }}</span>
                            @if($done) 
                                <span class="text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                    Completed
                                </span> 
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $quiz->title }}</h3>
                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                {{ $quiz->time_limit_minutes }} min
                            </span>
                            <span class="flex items-center gap-1">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                {{ $quiz->questions_count }} questions
                            </span>
                            @if($done && $score) <span class="text-green-600 font-semibold">Score: {{ $score->score }}/{{ $score->total_marks }}</span> @endif
                        </div>
                    </div>
                    <div class="border-t border-gray-100 px-5 py-3">
                        @if($done)
                            <a href="{{ route('student.quizzes.result', $quiz->attempts->first()) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">View My Results →</a>
                        @else
                            <a href="{{ route('student.quizzes.take', $quiz) }}" class="inline-block px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">Start Test</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-500">No tests available for you right now. Check back later!</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
