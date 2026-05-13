<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Welcome back,') }} <span class="text-indigo-600">{{ explode(' ', auth()->user()->name)[0] }}!</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                    Here is what's happening at 
                    <span class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1">
                        {{ auth()->user()->institute->name }}
                        @if(auth()->user()->institute->is_verified)
                            <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                        @endif
                    </span> 
                    today.
                </p>
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

            {{-- Promoted Add-on Banner --}}
            @if(isset($promotedAddOn))
                <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 rounded-3xl shadow-xl shadow-indigo-900/20 border border-indigo-700/50 p-8">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-purple-500/30 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-indigo-500/30 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-[10px] font-black uppercase tracking-widest mb-4">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                New Powerup Available
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2">{{ $promotedAddOn->name }}</h3>
                            <p class="text-indigo-200 text-sm max-w-2xl">{{ $promotedAddOn->description }}</p>
                        </div>
                        
                        <div class="flex flex-col items-end shrink-0 gap-3">
                            <div class="text-right">
                                <span class="text-3xl font-black text-white">₹{{ number_format($promotedAddOn->price) }}</span>
                                <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest block">One-time payment</span>
                            </div>
                            <a href="{{ route('marketplace.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-indigo-900 hover:bg-indigo-50 transition-colors rounded-xl text-sm font-black shadow-lg">
                                View in Store
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
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

            {{-- Top Stats & Toolkit Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
                {{-- Stats Area (3 Columns - now with 6 cards) --}}
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                    $kpis = [
                        [
                            'label' => 'Total Students',
                            'value' => $totalStudents,
                            'icon' => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m9-10a4 4 0 100-8 4 4 0 000 8zm8-7a4 4 0 00-3 3.5M16 11a4 4 0 013 3.5m0 0V19m0-8a4 4 0 013 3.5',
                            'color' => 'indigo',
                            'trend' => '+12% Growth'
                        ],
                        [
                            'label' => "Attendance",
                            'value' => $todayTotal > 0 ? round(($todayAttended/$todayTotal)*100).'%' : '0%',
                            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 16h.01',
                            'color' => 'emerald',
                            'trend' => $todayAttended . ' present'
                        ],
                        [
                            'label' => 'Revenue',
                            'value' => '₹' . number_format($collectedFees),
                            'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                            'color' => 'rose',
                            'trend' => 'This Month'
                        ],
                        [
                            'label' => 'Active Batches',
                            'value' => $totalBatches,
                            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                            'color' => 'cyan',
                            'trend' => 'Live Now'
                        ],
                        [
                            'label' => 'Pending Fees',
                            'value' => '₹' . number_format($pendingFees),
                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                            'color' => 'amber',
                            'trend' => 'Outstanding'
                        ],
                        [
                            'label' => 'Leads',
                            'value' => $conversionRate . '%',
                            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                            'color' => 'violet',
                            'trend' => $leadsToday . ' today'
                        ],
                    ];
                    @endphp

                    @foreach($kpis as $kpi)
                    <div class="group relative bg-white dark:bg-gray-800 rounded-[2rem] p-7 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 overflow-hidden">
                        {{-- Reflective Edge --}}
                        <div class="absolute inset-0 border-t border-white/40 dark:border-white/5 rounded-[2rem] pointer-events-none"></div>
                        
                        <div class="flex items-center gap-5 relative z-10">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-{{ $kpi['color'] }}-50 dark:bg-{{ $kpi['color'] }}-900/30 text-{{ $kpi['color'] }}-600 transition-all duration-500 group-hover:scale-110 group-hover:rotate-3 shadow-inner shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $kpi['icon'] }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ $kpi['label'] }}</p>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter leading-tight truncate">{{ $kpi['value'] }}</h3>
                                <div class="mt-1 inline-flex items-center px-2 py-0.5 bg-{{ $kpi['color'] }}-50 dark:bg-{{ $kpi['color'] }}-900/40 rounded-lg">
                                    <span class="text-[8px] font-black text-{{ $kpi['color'] }}-600 uppercase tracking-widest">{{ $kpi['trend'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Elite Compact Toolkit --}}
                <div class="bg-gray-950 rounded-[2.5rem] p-6 text-white shadow-2xl relative overflow-hidden flex flex-col border border-white/5 h-full">
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-600/10 rounded-full blur-[100px]"></div>
                    <div class="relative z-10 flex-1">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-2 h-6 bg-indigo-500 rounded-full"></div>
                            <h3 class="text-lg font-black tracking-tighter uppercase italic leading-none">Control<br>Panel</h3>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('students.create') }}" class="group flex flex-col items-center justify-center p-4 bg-white/5 hover:bg-white/10 rounded-2xl transition-all border border-white/5 shadow-lg text-center">
                                <div class="w-10 h-10 mb-2 rounded-xl bg-white text-gray-950 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                </div>
                                <span class="text-[8px] font-black uppercase tracking-widest">Enroll</span>
                            </a>
 
                            <a href="{{ route('attendances.create') }}" class="group flex flex-col items-center justify-center p-4 bg-white/5 hover:bg-white/10 rounded-2xl transition-all border border-white/5 shadow-lg text-center">
                                <div class="w-10 h-10 mb-2 rounded-xl bg-indigo-500 text-white flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <span class="text-[8px] font-black uppercase tracking-widest">Attendance</span>
                            </a>
 
                            <a href="{{ route('fees.create') }}" class="group flex flex-col items-center justify-center p-4 bg-white/5 hover:bg-white/10 rounded-2xl transition-all border border-white/5 shadow-lg text-center">
                                <div class="w-10 h-10 mb-2 rounded-xl bg-emerald-500 text-white flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <span class="text-[8px] font-black uppercase tracking-widest">Fees</span>
                            </a>
 
                            <a href="{{ route('enquiries.create') }}" class="group flex flex-col items-center justify-center p-4 bg-gradient-to-br from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 rounded-2xl transition-all shadow-lg text-center">
                                <div class="w-10 h-10 mb-2 rounded-xl bg-white/20 text-white flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <span class="text-[8px] font-black uppercase tracking-widest">Lead</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left Side: Analytics & History (2 Columns) --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Batch Profitability (Premium Feature) --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-10 relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-8 bg-emerald-500 rounded-full"></div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Batch Profitability</h3>
                            </div>
                            @if(!$institute->isPremium())
                                <span class="px-4 py-1.5 bg-amber-100 text-amber-700 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-200">Premium Only</span>
                            @endif
                        </div>

                        @if($institute->isPremium())
                            <div class="space-y-6">
                                @forelse($batchProfitability as $bp)
                                    <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50/50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-700">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-black text-gray-900 dark:text-white truncate">{{ $bp->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Revenue: ₹{{ number_format($bp->revenue) }} | Staff: ₹{{ number_format($bp->faculty_cost) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black {{ $bp->profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">₹{{ number_format($bp->profit) }}</p>
                                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Net Profit</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-400 py-4 italic text-xs">No financial data available for batches yet.</p>
                                @endforelse
                            </div>
                        @else
                            <div class="py-12 text-center">
                                <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-amber-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002-2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                </div>
                                <h4 class="text-base font-black text-gray-900 dark:text-white">Unlock Profitability Analytics</h4>
                                <p class="text-xs text-gray-500 mt-2 max-w-xs mx-auto">Analyze which batches are generating the most revenue after deducting faculty costs.</p>
                                <a href="{{ route('subscription.index') }}" class="inline-flex mt-6 px-6 py-3 bg-amber-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-200">Upgrade to Premium</a>
                            </div>
                        @endif
                    </div>

                    {{-- Analytics Charts Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Enrollment Trends --}}
                        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-10 hover:shadow-2xl transition-all duration-500 relative overflow-hidden group">
                            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="flex items-center justify-between mb-10">
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Enrollment Growth</h3>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Real-time Performance</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                </div>
                            </div>
                            <div id="enrollmentChart"></div>
                        </div>

                        {{-- Revenue Trends --}}
                        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-10 hover:shadow-2xl transition-all duration-500 relative overflow-hidden group">
                            <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="flex items-center justify-between mb-10">
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Revenue Streams</h3>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Collection Analytics</p>
                                </div>
                                <div class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[8px] font-black uppercase tracking-widest shadow-sm">
                                    {{ $conversionRate }}% Conversion
                                </div>
                            </div>
                            <div id="revenueChart"></div>
                        </div>
                    </div>

                    {{-- Batch Engagement --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-10">
                        <div class="flex items-center gap-3 mb-10">
                            <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Engagement Hub</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            @foreach($batchPerformance->take(4) as $batch)
                            <div class="p-6 rounded-[2.5rem] bg-gray-50/50 dark:bg-gray-700/20 border border-gray-100 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-700 transition-all shadow-sm hover:shadow-md group">
                                <div class="flex justify-between items-center mb-5">
                                    <span class="text-sm font-black text-gray-800 dark:text-gray-200 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $batch->name }}</span>
                                    <span class="text-[10px] font-black text-indigo-600 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-xl shadow-sm border border-gray-50 dark:border-gray-700">{{ $batch->attendance_rate }}% Score</span>
                                </div>
                                <div class="h-3 w-full bg-gray-200 dark:bg-gray-900 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 via-indigo-600 to-violet-500 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(79,70,229,0.3)]" style="width: {{ $batch->attendance_rate }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- AI Retention Hub (At-Risk Students) --}}
                    @if($atRiskStudents->count() > 0)
                    <div class="bg-rose-50 dark:bg-rose-900/10 rounded-[2.5rem] border border-rose-100 dark:border-rose-900/30 p-10 mb-8">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-8 bg-rose-500 rounded-full"></div>
                                <h3 class="text-xl font-black text-rose-900 dark:text-rose-100 uppercase tracking-tighter">AI Retention Hub</h3>
                            </div>
                            <span class="px-4 py-1.5 bg-rose-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest animate-pulse">Critical Alerts</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($atRiskStudents as $student)
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-rose-100/50 hover:shadow-md transition-all group">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-black">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $student->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $student->batch?->name ?? 'No Batch' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        @for($i=0; $i<$student->risk_level; $i++)
                                            <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    @foreach($student->risk_reasons as $reason)
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-rose-600 bg-rose-50 dark:bg-rose-900/30 px-3 py-1.5 rounded-lg">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                        {{ $reason }}
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-5 flex justify-end">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->phone) }}" target="_blank" class="text-[9px] font-black text-rose-600 uppercase hover:underline">Intervene via WhatsApp →</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Recent Enrollments List --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Latest Students</h3>
                            <a href="{{ route('students.index') }}" class="text-[10px] font-black text-indigo-600 hover:underline uppercase tracking-widest">Full Directory →</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentStudents as $s)
                            <div class="group flex items-center gap-4 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 border border-transparent hover:border-gray-100">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-indigo-100">
                                    {{ strtoupper(substr($s->name,0,1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black text-gray-900 dark:text-white truncate group-hover:text-indigo-600 transition-colors">{{ $s->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $s->batch?->name ?? 'Unassigned' }} · {{ $s->created_at->diffForHumans(null, true) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('students.edit', $s) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-white rounded-lg shadow-sm transition-all opacity-0 group-hover:opacity-100">
                                        <x-icons.edit class="w-4 h-4" />
                                    </a>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-gray-400 py-8 italic text-xs">No students yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Right Side: Batches & Announcements (1 Column) --}}
                <div class="space-y-8">
                    {{-- Active Batches --}}
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-8">Active Batches</h3>
                        <div class="space-y-4">
                            @forelse($batches as $b)
                            <div class="p-5 rounded-3xl bg-gray-50 dark:bg-gray-700/30 border border-transparent hover:border-indigo-100 transition-all group">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-white px-3 py-1 rounded-full shadow-sm">{{ $b->subject ?? 'General' }}</span>
                                    <span class="text-[10px] font-bold text-gray-400">{{ $b->students_count }} Students</span>
                                </div>
                                <p class="text-sm font-black text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $b->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-1 flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    {{ $b->time_slot ?? 'Not Set' }}
                                </p>
                            </div>
                            @empty
                            <p class="text-center text-gray-400 py-8 text-xs italic">No batches.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Notice Board --}}
                    <div class="bg-gradient-to-br from-indigo-900 via-gray-900 to-black rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute bottom-0 right-0 -mb-10 -mr-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl"></div>
                        <h3 class="text-lg font-black uppercase tracking-tighter mb-6 flex items-center gap-2 relative z-10">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                            Notice Board
                        </h3>
                        
                        <form method="POST" action="{{ route('announcements.store') }}" class="space-y-4 relative z-10">
                            @csrf
                            <input type="hidden" name="type" value="info">
                            <input type="text" name="title" placeholder="Topic..." required class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 text-xs placeholder-white/30 focus:ring-1 focus:ring-indigo-500 focus:bg-white/10 transition-all">
                            <textarea name="content" placeholder="Type your broadcast..." rows="2" required class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 text-xs placeholder-white/30 focus:ring-1 focus:ring-indigo-500 focus:bg-white/10 transition-all resize-none"></textarea>
                            <button type="submit" class="w-full bg-white text-gray-900 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-50 transition-colors">Broadcast Notice</button>
                        </form>

                        @if($announcements->count())
                        <div class="mt-8 space-y-3 relative z-10">
                            @foreach($announcements as $ann)
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/50 transition-all group">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[8px] font-black uppercase tracking-widest {{ $ann->type === 'warning' ? 'text-amber-400' : 'text-indigo-300' }}">{{ $ann->type }}</span>
                                    <form method="POST" action="{{ route('announcements.destroy', $ann) }}">
                                        @csrf @method('DELETE')
                                        <button class="text-white/20 hover:text-red-400 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <p class="text-xs font-black leading-tight">{{ $ann->title }}</p>
                                <p class="text-[10px] text-white/50 mt-1 line-clamp-1">{{ $ann->content }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const enrollmentData = {!! json_encode($chartData) !!};
                    const revenueData = {!! json_encode($revenueData) !!};
                    
                    console.log('Enrollment Data:', enrollmentData);
                    console.log('Revenue Data:', revenueData);

                    var options = {
                        series: [{
                            name: 'New Students',
                            data: enrollmentData.counts
                        }],
                        chart: {
                            height: 240,
                            type: 'area',
                            toolbar: { show: false },
                            animations: { enabled: true }
                        },
                        colors: ['#4f46e5'],
                        fill: {
                            type: 'gradient',
                            gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05 }
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 },
                        markers: { size: 4, colors: ['#4f46e5'], strokeWidth: 2, hover: { size: 7 } },
                        grid: { show: true, borderColor: '#f1f5f9', strokeDashArray: 4 },
                        xaxis: {
                            categories: enrollmentData.days,
                            labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
                        },
                        yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '10px' } } },
                        tooltip: { theme: 'dark' }
                    };

                    new ApexCharts(document.querySelector("#enrollmentChart"), options).render();

                    // Revenue Chart
                    var revOptions = {
                        series: [{
                            name: 'Revenue',
                            data: revenueData.amounts
                        }],
                        chart: {
                            height: 240,
                            type: 'bar',
                            toolbar: { show: false }
                        },
                        colors: ['#6366f1'],
                        plotOptions: {
                            bar: {
                                borderRadius: 8,
                                columnWidth: '50%',
                                distributed: true
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        grid: { show: true, borderColor: '#f1f5f9', strokeDashArray: 4 },
                        xaxis: {
                            categories: revenueData.months,
                            labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
                        },
                        yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '10px' } } },
                        tooltip: { 
                            theme: 'dark',
                            y: { formatter: (val) => '₹' + val.toLocaleString() }
                        }
                    };

                    new ApexCharts(document.querySelector("#revenueChart"), revOptions).render();
                });
            </script>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>

</x-app-layout>
