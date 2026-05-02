<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Welcome back,') }} <span class="text-indigo-600">{{ explode(' ', auth()->user()->name)[0] }}!</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Here is what's happening at <span class="font-bold text-gray-700 dark:text-gray-300">{{ auth()->user()->institute->name }}</span> today.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="hidden md:inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse mr-2"></span>
                    Live Dashboard
                </span>
                <p class="text-sm font-medium text-gray-500">{{ now()->format('l, M d, Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Profile Completion Warning --}}
            @if($profilePct < 70)
            <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-amber-100/50 dark:shadow-none border border-amber-100 dark:border-amber-900/30 p-6">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-amber-100/50 rounded-full blur-2xl"></div>
                <div class="relative flex flex-col sm:flex-row sm:items-center gap-6">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">Complete your institute profile</h3>
                        <p class="text-sm text-gray-500 mt-1 italic">"First impressions matter! Students see this info on the registration page."</p>
                        <div class="mt-4 flex items-center gap-4">
                            <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full transition-all duration-1000" style="width:{{ $profilePct }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-amber-600">{{ $profilePct }}%</span>
                        </div>
                    </div>
                    <a href="{{ route('institute.settings') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white transition-all hover:scale-105 active:scale-95 shadow-lg shadow-amber-200" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                        Improve Profile
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </a>
                </div>
            </div>
            @endif

            {{-- KPI Cards with Modern Aesthetic --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                $cards = [
                    [
                        'label' => 'Total Students',
                        'value' => $totalStudents,
                        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        'color' => 'indigo',
                        'trend' => '+12% from last month'
                    ],
                    [
                        'label' => 'Active Batches',
                        'value' => $totalBatches,
                        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                        'color' => 'cyan',
                        'trend' => '3 starting this week'
                    ],
                    [
                        'label' => "Today's Attendance",
                        'value' => $todayTotal > 0 ? round(($todayAttended/$todayTotal)*100).'%' : '0%',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        'color' => 'emerald',
                        'trend' => $todayAttended . ' students present'
                    ],
                    [
                        'label' => 'Collection (Month)',
                        'value' => '₹' . number_format($collectedFees),
                        'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                        'color' => 'rose',
                        'trend' => '₹' . number_format($pendingFees) . ' pending'
                    ],
                ];
                @endphp

                @foreach($cards as $card)
                <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-900/20 text-{{ $card['color'] }}-600 transition-transform group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $card['icon'] }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                        <div class="flex -space-x-2 overflow-hidden">
                            <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400">•••</div>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $card['value'] }}</h3>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $card['label'] }}</p>
                    <div class="mt-4 pt-4 border-t border-gray-50 dark:border-gray-700 flex items-center gap-2 text-[10px] font-black text-{{ $card['color'] }}-600 uppercase tracking-tighter">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                        {{ $card['trend'] }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left Column: Recent Students & Actions --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Interactive Quick Actions --}}
                    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="relative">
                            <h3 class="text-xl font-black mb-6">Manager Toolkit</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <a href="{{ route('students.create') }}" class="flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-3xl transition-all border border-white/10 group">
                                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                                        <x-icons.plus class="w-5 h-5" />
                                    </div>
                                    <span class="text-xs font-bold tracking-tight">New Student</span>
                                </a>
                                <a href="{{ route('attendances.create') }}" class="flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-3xl transition-all border border-white/10 group">
                                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <span class="text-xs font-bold tracking-tight">Attendance</span>
                                </a>
                                <a href="{{ route('fees.create') }}" class="flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-3xl transition-all border border-white/10 group">
                                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <span class="text-xs font-bold tracking-tight">Fee Entry</span>
                                </a>
                                <a href="{{ route('batches.create') }}" class="flex flex-col items-center gap-3 p-4 bg-white/10 hover:bg-white/20 rounded-3xl transition-all border border-white/10 group">
                                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <span class="text-xs font-bold tracking-tight">New Batch</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Enrollments List --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                            <div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white">Enrollment Growth</h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Activity Tracking</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                                <span class="text-xs font-bold text-gray-500">New Students</span>
                            </div>
                        </div>
                        
                        <div id="enrollmentChart" class="w-full h-64"></div>

                        <div class="mt-12">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-black text-gray-900 dark:text-white">Latest Students</h3>
                                <a href="{{ route('students.index') }}" class="text-xs font-black text-indigo-600 hover:underline uppercase tracking-widest">Full List →</a>
                            </div>
                            <div class="space-y-4">
                                @forelse($recentStudents as $s)
                                <div class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 border border-transparent hover:border-gray-100">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-indigo-100">
                                        {{ strtoupper(substr($s->name,0,1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-black text-gray-900 dark:text-white truncate group-hover:text-indigo-600 transition-colors">{{ $s->name }}</p>
                                        <p class="text-xs text-gray-400 font-medium">{{ $s->batch?->name ?? 'Unassigned' }} · <span class="text-gray-300 italic">{{ $s->created_at->diffForHumans() }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('students.edit', $s) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-white rounded-lg shadow-sm transition-all opacity-0 group-hover:opacity-100">
                                            <x-icons.edit class="w-4 h-4" />
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-12">
                                    <p class="text-sm text-gray-400 font-bold italic">No students yet.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var options = {
                            series: [{
                                name: 'New Students',
                                data: [31, 40, 28, 51, 42, 109, 100]
                            }],
                            chart: {
                                height: 260,
                                type: 'area',
                                toolbar: { show: false },
                                sparkline: { enabled: false },
                                animations: { enabled: true, easing: 'easeinout', speed: 800 }
                            },
                            dataLabels: { enabled: false },
                            stroke: { curve: 'smooth', width: 3, colors: ['#4f46e5'] },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.45,
                                    opacityTo: 0.05,
                                    stops: [20, 100, 100, 100],
                                    colorStops: [
                                        { offset: 0, color: '#4f46e5', opacity: 0.4 },
                                        { offset: 100, color: '#4f46e5', opacity: 0 }
                                    ]
                                }
                            },
                            grid: { show: false },
                            xaxis: {
                                categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                                axisBorder: { show: false },
                                axisTicks: { show: false },
                                labels: { style: { colors: '#94a3b8', fontWeight: 700, fontSize: '10px' } }
                            },
                            yaxis: { show: false },
                            tooltip: {
                                theme: 'dark',
                                x: { show: false },
                                y: { title: { formatter: () => 'Students:' } }
                            }
                        };

                        var chart = new ApexCharts(document.querySelector("#enrollmentChart"), options);
                        chart.render();
                    });
                </script>

                {{-- Right Column: Batches & Announcements --}}
                <div class="space-y-8">
                    
                    {{-- Active Batches --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6">Batches</h3>
                        <div class="space-y-3">
                            @forelse($batches as $b)
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/30 border border-transparent hover:border-indigo-100 transition-all group">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-black text-indigo-600 uppercase tracking-tighter">{{ $b->subject ?? 'General' }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 bg-white px-2 py-0.5 rounded-full">{{ $b->students_count }} Seats</span>
                                </div>
                                <p class="text-sm font-black text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 transition-colors">{{ $b->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    {{ $b->time_slot ?? 'Flexible Time' }}
                                </p>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 text-center py-4 italic">No active batches.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Announcement Portal --}}
                    <div class="bg-gradient-to-br from-gray-900 to-black rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute bottom-0 right-0 -mb-10 -mr-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl"></div>
                        <h3 class="text-lg font-black mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Notice Board
                        </h3>
                        
                        <form method="POST" action="{{ route('announcements.store') }}" class="space-y-4">
                            @csrf
                            <input type="text" name="title" placeholder="Notice Title" required class="w-full bg-white/10 border-none rounded-xl px-4 py-2 text-sm placeholder-white/30 focus:ring-1 focus:ring-indigo-500">
                            <textarea name="content" placeholder="Broadcast message to all students..." rows="2" required class="w-full bg-white/10 border-none rounded-xl px-4 py-2 text-sm placeholder-white/30 focus:ring-1 focus:ring-indigo-500 resize-none"></textarea>
                            <div class="grid grid-cols-2 gap-2">
                                <select name="type" class="bg-white/10 border-none rounded-xl px-3 py-2 text-xs focus:ring-1 focus:ring-indigo-500 text-white">
                                    <option value="info" class="text-gray-900">Information</option>
                                    <option value="warning" class="text-gray-900">Urgent</option>
                                    <option value="success" class="text-gray-900">Update</option>
                                </select>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 py-2 rounded-xl text-xs font-black transition-colors uppercase tracking-widest">Broadcast</button>
                            </div>
                        </form>

                        @if($announcements->count())
                        <div class="mt-8 pt-6 border-t border-white/10 space-y-3 max-h-60 overflow-y-auto custom-scrollbar">
                            @foreach($announcements as $ann)
                            <div class="group relative p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/50 transition-all">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-black uppercase tracking-widest mb-1 {{ $ann->type === 'warning' ? 'text-amber-400' : ($ann->type === 'success' ? 'text-emerald-400' : 'text-indigo-400') }}">{{ $ann->type }}</p>
                                    <form method="POST" action="{{ route('announcements.destroy', $ann) }}">
                                        @csrf @method('DELETE')
                                        <button class="opacity-0 group-hover:opacity-100 text-white/30 hover:text-red-400 transition-all">✕</button>
                                    </form>
                                </div>
                                <p class="text-xs font-black leading-tight">{{ $ann->title }}</p>
                                <p class="text-[10px] text-white/50 mt-1 line-clamp-2">{{ $ann->content }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>

</x-app-layout>
