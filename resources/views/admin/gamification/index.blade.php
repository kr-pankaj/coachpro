<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Engagement Analytics') }} <span class="text-indigo-600">📈</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Monitor student motivation and platform activity.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-indigo-100">
                    Live Data
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Total XP Circulating</p>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_xp']) }}</div>
                    <p class="text-[9px] text-indigo-600 font-bold mt-2 uppercase tracking-widest">Platform Economy</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Average Student Level</p>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['avg_level'] }}</div>
                    <p class="text-[9px] text-emerald-600 font-bold mt-2 uppercase tracking-widest">Growth Velocity</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Daily Active Streaks</p>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['active_streaks'] }}</div>
                    <p class="text-[9px] text-rose-600 font-bold mt-2 uppercase tracking-widest">Retention Rate</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Badges Unlocked</p>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['badges_awarded'] }}</div>
                    <p class="text-[9px] text-amber-600 font-bold mt-2 uppercase tracking-widest">Achievement Count</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- XP Trend --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-[3rem] p-10 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-black tracking-tight mb-8">XP Distribution Trend (Last 30 Days)</h3>
                    <div class="h-64 flex items-end gap-2">
                        @foreach($xpChart as $data)
                            <div class="flex-1 bg-indigo-100 dark:bg-indigo-900/30 rounded-t-xl relative group" style="height: {{ ($data->total / max($xpChart->pluck('total')->toArray() ?: [1])) * 100 }}%">
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 px-3 py-1 bg-slate-900 text-white text-[9px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-black">
                                    {{ $data->total }} XP
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-6 px-2">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $xpChart->first()?->date ?? 'N/A' }}</span>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $xpChart->last()?->date ?? 'N/A' }}</span>
                    </div>
                </div>

                {{-- Top Performers --}}
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-[3rem] p-10 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-black tracking-tight mb-8">Elite Students</h3>
                    <div class="space-y-6">
                        @foreach($topStudents as $student)
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-lg shadow-sm border border-gray-100 dark:border-gray-700 group-hover:scale-110 transition-all">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $student->name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Level {{ $student->level }} • {{ number_format($student->xp_total) }} XP</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <a href="{{ route('leaderboard') }}" class="block text-center mt-10 py-4 bg-gray-50 dark:bg-gray-900 rounded-2xl text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                        View Full Leaderboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
