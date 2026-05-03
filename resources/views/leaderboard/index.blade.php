<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Hall of Fame') }} 🏆
                </h2>
                <p class="text-sm text-gray-500 mt-1">Recognizing our top performers and consistent learners.</p>
            </div>
            
            <form action="{{ route('leaderboard') }}" method="GET" class="flex items-center gap-3">
                <select name="batch_id" onchange="this.form.submit()" class="bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 rounded-2xl text-xs font-black uppercase tracking-widest px-6 py-3 focus:ring-quonix-purple">
                    <option value="">Global Rankings</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Top 3 Podium --}}
            @if($students->count() >= 3)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 items-end">
                {{-- 2nd Place --}}
                <div class="order-2 md:order-1 flex flex-col items-center">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-[2rem] bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 font-black text-3xl shadow-xl">
                            {{ substr($students[1]->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-slate-400 rounded-full border-4 border-white dark:border-gray-900 flex items-center justify-center text-white font-black">2</div>
                    </div>
                    <h3 class="font-black text-gray-900 dark:text-white text-center">{{ $students[1]->name }}</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $students[1]->batch?->name }}</p>
                    <div class="mt-4 px-4 py-2 bg-slate-50 dark:bg-slate-900/50 rounded-xl text-xs font-black text-slate-600">{{ $students[1]->average_percentage }}% Avg</div>
                </div>

                {{-- 1st Place --}}
                <div class="order-1 md:order-2 flex flex-col items-center scale-110">
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-amber-400 blur-3xl opacity-20 animate-pulse"></div>
                        <div class="w-32 h-32 rounded-[2.5rem] bg-amber-50 dark:bg-amber-900/20 border-4 border-amber-400 flex items-center justify-center text-amber-500 font-black text-5xl shadow-2xl relative z-10">
                            {{ substr($students[0]->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-4 -right-4 w-12 h-12 bg-amber-400 rounded-full border-4 border-white dark:border-gray-900 flex items-center justify-center text-white font-black text-xl shadow-lg relative z-20">1</div>
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-4xl">👑</div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white text-center">{{ $students[0]->name }}</h3>
                    <p class="text-xs font-black text-amber-600 uppercase tracking-widest mt-1">{{ $students[0]->batch?->name }}</p>
                    <div class="mt-4 px-6 py-3 bg-amber-400 text-white rounded-2xl text-sm font-black shadow-lg shadow-amber-200 dark:shadow-none">{{ $students[0]->average_percentage }}% Avg</div>
                </div>

                {{-- 3rd Place --}}
                <div class="order-3 flex flex-col items-center">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-[2rem] bg-orange-50 dark:bg-orange-900/10 flex items-center justify-center text-orange-400 font-black text-3xl shadow-xl">
                            {{ substr($students[2]->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-orange-400 rounded-full border-4 border-white dark:border-gray-900 flex items-center justify-center text-white font-black">3</div>
                    </div>
                    <h3 class="font-black text-gray-900 dark:text-white text-center">{{ $students[2]->name }}</h3>
                    <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mt-1">{{ $students[2]->batch?->name }}</p>
                    <div class="mt-4 px-4 py-2 bg-orange-50 dark:bg-orange-900/50 rounded-xl text-xs font-black text-orange-600">{{ $students[2]->average_percentage }}% Avg</div>
                </div>
            </div>
            @endif

            {{-- Full List --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Rank</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Student</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Batch</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tests</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Score</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Avg %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($students as $index => $student)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all">
                            <td class="px-8 py-6">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs {{ $index < 3 ? 'bg-quonix-purple text-white' : 'text-gray-400' }}">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 font-black">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-black text-gray-900 dark:text-white">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $student->batch?->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $student->tests_taken }}</td>
                            <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $student->total_score }}/{{ $student->total_possible }}</td>
                            <td class="px-8 py-6 text-right">
                                <div class="inline-flex flex-col items-end">
                                    <span class="text-sm font-black {{ $student->average_percentage >= 75 ? 'text-emerald-600' : ($student->average_percentage >= 40 ? 'text-quonix-purple' : 'text-rose-600') }}">
                                        {{ $student->average_percentage }}%
                                    </span>
                                    <div class="w-24 h-1 bg-gray-100 dark:bg-gray-700 rounded-full mt-2 overflow-hidden">
                                        <div class="h-full {{ $student->average_percentage >= 75 ? 'bg-emerald-500' : ($student->average_percentage >= 40 ? 'bg-quonix-purple' : 'bg-rose-500') }}" style="width: {{ $student->average_percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-gray-400 font-bold italic text-sm">No performance data available yet. Start taking assessments!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
