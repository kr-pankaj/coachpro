<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 dark:text-gray-200 leading-tight tracking-tight">Assessment Center</h2>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Track your progress and test your skills</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @forelse($quizzes as $quiz)
                @php 
                    $attempt = $quiz->attempts->first();
                    $done = (bool)$attempt;
                    $isActive = $quiz->is_active;
                @endphp
                
                <div class="group bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl shadow-gray-100/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden hover:border-indigo-200 transition-all duration-300">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex gap-2">
                                @if($done)
                                    <span class="badge-premium badge-emerald">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                        Completed
                                    </span>
                                @elseif(!$isActive)
                                    <span class="badge-premium badge-rose">
                                        Closed
                                    </span>
                                @else
                                    <span class="badge-premium badge-indigo">
                                        <span class="w-1 h-1 rounded-full bg-current mr-1.5 animate-pulse"></span>
                                        Available Now
                                    </span>
                                @endif
                            </div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $quiz->batch?->name ?? 'Open Access' }}</span>
                        </div>

                        <h3 class="text-xl font-black text-gray-900 dark:text-white leading-tight mb-4 group-hover:text-indigo-600 transition-colors">{{ $quiz->title }}</h3>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Duration</p>
                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $quiz->time_limit_minutes }} Min</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Content</p>
                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $quiz->questions_count }} Qs</p>
                                </div>
                            </div>
                            @if($done && $attempt)
                            <div class="flex items-center gap-3 col-span-2 sm:col-span-1">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Your Score</p>
                                    <p class="text-sm font-black text-emerald-600">{{ $attempt->score }}/{{ $attempt->total_marks }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-8 border-t border-gray-50 dark:border-gray-700">
                            @if($done)
                                <a href="{{ route('student.quizzes.result', $attempt) }}" class="flex items-center gap-2 text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:gap-3 transition-all">
                                    Review Performance
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                </a>
                            @elseif($isActive)
                                <a href="{{ route('student.quizzes.take', $quiz) }}" class="w-full sm:w-auto px-10 py-4 bg-gray-900 dark:bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-gray-200 dark:shadow-none hover:scale-[1.02] active:scale-95 transition-all text-center">
                                    Start Assessment
                                </a>
                            @else
                                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Registration Closed</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white dark:bg-gray-800 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-gray-700">
                    <div class="w-24 h-24 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-gray-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">All Caught Up!</h3>
                    <p class="text-sm text-gray-400 font-bold mt-1">No pending assessments assigned to your batch yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
