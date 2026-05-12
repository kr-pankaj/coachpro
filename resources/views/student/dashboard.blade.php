<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Learning Pulse') }} <span class="text-quonix-purple">🚀</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Keep pushing forward, <span class="font-bold text-gray-700 dark:text-gray-300">{{ explode(' ', $student->name)[0] }}</span>!</p>
            </div>
            <div class="flex items-center gap-3">
                @if($streak > 0)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-orange-50 text-orange-600 border border-orange-100 uppercase tracking-widest shadow-sm">
                    <span class="mr-1">🔥</span>
                    {{ $streak }} Day Streak
                </span>
                @endif
                @foreach($badges as $badge)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-{{ $badge['color'] }}-50 text-{{ $badge['color'] }}-600 border border-{{ $badge['color'] }}-100 uppercase tracking-widest shadow-sm">
                    <span class="mr-1">{{ $badge['icon'] }}</span>
                    {{ $badge['label'] }}
                </span>
                @endforeach
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-quonix-purple/10 text-quonix-purple border border-quonix-purple/10 uppercase tracking-widest">
                    {{ $student->batch?->name ?? 'Awaiting Batch' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- KPI Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Performance Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-7 border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-quonix-purple/5 rounded-full blur-2xl group-hover:bg-quonix-purple/10 transition-all"></div>
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-quonix-purple/10 text-quonix-purple">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Avg. Score</p>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $performanceRate }}%</h3>
                            <div class="w-24 h-1 bg-gray-100 dark:bg-gray-700 rounded-full mt-2 overflow-hidden">
                                <div class="h-full bg-quonix-purple rounded-full" style="width: {{ $performanceRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Attendance Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-7 border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-all"></div>
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Attendance</p>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $attendanceRate }}%</h3>
                            <div class="w-24 h-1 bg-gray-100 dark:bg-gray-700 rounded-full mt-2 overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Outstanding Fees Card --}}
                @php $pending = $fees->sum('due_amount'); @endphp
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-7 border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:bg-rose-500/10 transition-all"></div>
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center {{ $pending > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Finance Status</p>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $pending > 0 ? '₹'.number_format($pending) : 'Settled' }}</h3>
                            <p class="text-[8px] font-black uppercase tracking-widest {{ $pending > 0 ? 'text-rose-500' : 'text-emerald-500' }} mt-1">{{ $pending > 0 ? 'Payment Overdue' : 'Account in Good Standing' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left: Academic Progress (2 Cols) --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Upcoming Tasks --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Action Required</h3>
                            <span class="text-[10px] font-black text-quonix-purple uppercase tracking-widest">{{ $upcomingQuizzes->count() }} Pending</span>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($upcomingQuizzes as $q)
                            <div class="group flex items-center justify-between p-5 bg-gray-50/50 dark:bg-gray-700/30 rounded-3xl border border-transparent hover:border-quonix-purple/20 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center text-quonix-purple shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ $q->title }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $q->time_limit_minutes }} Minutes · {{ $q->questions_count ?? 0 }} Questions</p>
                                    </div>
                                </div>
                                <a href="{{ route('student.quizzes.take', $q) }}" class="px-6 py-3 bg-quonix-purple text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-quonix-purple/20">Start Test</a>
                            </div>
                            @empty
                            <div class="py-10 text-center">
                                <p class="text-gray-400 font-bold italic text-sm">You are all caught up! No pending assessments.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent Performance History --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-8">Performance History</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700">
                                        <th class="pb-4 px-2">Quiz Title</th>
                                        <th class="pb-4 px-2">Score</th>
                                        <th class="pb-4 px-2">Status</th>
                                        <th class="pb-4 px-2 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                    @foreach($attempts as $attempt)
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all">
                                        <td class="py-5 px-2">
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $attempt->quiz->title }}</p>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase">{{ $attempt->completed_at->format('M d, Y') }}</p>
                                        </td>
                                        <td class="py-5 px-2">
                                            <span class="text-sm font-black {{ ($attempt->score / $attempt->total_marks) >= 0.75 ? 'text-emerald-600' : 'text-quonix-purple' }}">
                                                {{ $attempt->score }}/{{ $attempt->total_marks }}
                                            </span>
                                        </td>
                                        <td class="py-5 px-2">
                                            <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest rounded-md {{ ($attempt->score / $attempt->total_marks) >= 0.4 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                {{ ($attempt->score / $attempt->total_marks) >= 0.4 ? 'Passed' : 'Failed' }}
                                            </span>
                                        </td>
                                        <td class="py-5 px-2 text-right">
                                            <a href="{{ route('student.quizzes.result', $attempt) }}" class="text-[10px] font-black text-quonix-purple hover:underline uppercase tracking-widest">Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Right: Sidebar Info --}}
                <div class="space-y-8">
                    {{-- Finance Hub Quick View --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-6">Finance Hub</h3>
                        <div class="space-y-4">
                            @foreach($fees->take(3) as $fee)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50/50 dark:bg-gray-700/50 border border-transparent">
                                <div>
                                    <p class="text-xs font-black text-gray-900 dark:text-white">{{ $fee->month_year }}</p>
                                    <p class="text-[8px] text-gray-400 font-bold uppercase">₹{{ number_format($fee->total_amount) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest rounded-md {{ $fee->status === 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $fee->status }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Notice Board --}}
                    @if($announcements->count())
                    <div class="bg-gradient-to-br from-indigo-900 via-gray-900 to-black rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden mb-8">
                        <div class="absolute bottom-0 right-0 -mb-10 -mr-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl"></div>
                        <h3 class="text-lg font-black uppercase tracking-tighter mb-6 flex items-center gap-2 relative z-10">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                            Notice Board
                        </h3>
                        
                        <div class="space-y-4 relative z-10">
                            @foreach($announcements as $ann)
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/50 transition-all group">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[8px] font-black uppercase tracking-widest {{ $ann->type === 'warning' ? 'text-amber-400' : 'text-indigo-300' }}">{{ $ann->type }}</span>
                                    <span class="text-[8px] font-bold text-white/30">{{ $ann->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-black leading-tight">{{ $ann->title }}</p>
                                <p class="text-[10px] text-white/50 mt-1">{{ $ann->content }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Profile Update Request --}}
                    <div class="bg-gray-950 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute bottom-0 right-0 -mb-10 -mr-10 w-32 h-32 bg-quonix-purple/20 rounded-full blur-3xl"></div>
                        <h3 class="text-lg font-black uppercase tracking-tighter mb-2 relative z-10">Quick Support</h3>
                        <p class="text-[10px] text-gray-400 font-bold mb-6 relative z-10">Request a profile update from your institute.</p>
                        
                        <form method="POST" action="{{ route('profile_requests.store') }}" class="space-y-4 relative z-10">
                            @csrf
                            <input type="text" name="phone" placeholder="New Phone..." class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 text-xs placeholder-white/30 focus:ring-1 focus:ring-quonix-purple focus:bg-white/10 transition-all">
                            <textarea name="address" placeholder="New Address..." rows="2" class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 text-xs placeholder-white/30 focus:ring-1 focus:ring-quonix-purple focus:bg-white/10 transition-all resize-none"></textarea>
                            <button type="submit" class="w-full bg-white text-gray-900 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-quonix-purple/5 transition-colors">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
