<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                    Elite Platform<br>Command Center
                </h2>
                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mt-1">Super Admin Overview</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Platform Status</p>
                    <p class="text-xs font-black text-gray-900 dark:text-white uppercase italic">Optimal Performance</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-8 sm:px-10 lg:px-12 space-y-12">
            
            {{-- Global KPI Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Institutes</p>
                    <h3 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter italic">{{ $stats['total_institutes'] }}</h3>
                    <p class="text-[10px] font-black text-indigo-600 mt-2 uppercase tracking-tighter">+{{ $stats['new_institutes'] }} this week</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Global Students</p>
                    <h3 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter italic">{{ number_format($stats['total_students']) }}</h3>
                    <p class="text-[10px] font-black text-emerald-600 mt-2 uppercase tracking-tighter">Active across platform</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Revenue</p>
                    <h3 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter italic">₹{{ number_format($stats['total_revenue']) }}</h3>
                    <p class="text-[10px] font-black text-indigo-600 mt-2 uppercase tracking-tighter">Verified Collections</p>
                </div>

                <div class="bg-indigo-600 p-8 rounded-[2.5rem] shadow-xl shadow-indigo-200 dark:shadow-none text-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-2 relative z-10">Avg. Size</p>
                    <h3 class="text-4xl font-black tracking-tighter italic relative z-10">{{ $stats['total_institutes'] > 0 ? round($stats['total_students'] / $stats['total_institutes']) : 0 }}</h3>
                    <p class="text-[10px] font-black text-indigo-100 mt-2 uppercase tracking-tighter relative z-10">Students per Institute</p>
                </div>
            </div>

            {{-- Institute Directory --}}
            <div class="bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-10 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Institute Directory</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Management & Access Control</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-10 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Institute</th>
                                <th class="px-10 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Size</th>
                                <th class="px-10 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-10 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach ($institutes as $institute)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white font-black shadow-lg">
                                            {{ substr($institute->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $institute->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Joined {{ $institute->created_at->format('M Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                        <span class="text-xs font-black text-gray-600 dark:text-gray-300">{{ $institute->students_count }} Students</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    @if($institute->is_lifetime_free)
                                        <span class="px-3 py-1.5 bg-purple-50 text-purple-600 rounded-xl text-[8px] font-black uppercase tracking-widest border border-purple-100">Lifetime Elite</span>
                                    @elseif($institute->razorpay_subscription_id)
                                        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[8px] font-black uppercase tracking-widest border border-emerald-100">Active Pro</span>
                                    @elseif($institute->created_at->addDays(14)->isFuture())
                                        <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-[8px] font-black uppercase tracking-widest border border-indigo-100">Trial</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl text-[8px] font-black uppercase tracking-widest border border-rose-100">Expired</span>
                                    @endif
                                </td>
                                <td class="px-10 py-8 text-right space-x-2">
                                    <div class="flex items-center justify-end gap-3">
                                        <form action="{{ route('superadmin.toggle_lifetime_free', $institute) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ $institute->is_lifetime_free ? 'bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white' : 'bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                                                {{ $institute->is_lifetime_free ? 'Revoke Elite' : 'Grant Elite' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('superadmin.impersonate', $institute) }}" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-gray-200">
                                            Impersonate
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Secondary Management Layer --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Global Broadcast Form --}}
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-[3rem] p-10 border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mt-16 -mr-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Global Broadcast</h3>
                        </div>
                        
                        <form action="{{ route('superadmin.broadcast') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Announcement Title</label>
                                <input type="text" name="title" required placeholder="e.g. System Maintenance" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Message Content</label>
                                <textarea name="content" required rows="3" placeholder="Important platform-wide update..." class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Priority Type</label>
                                <select name="type" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="info">Information</option>
                                    <option value="warning">Warning</option>
                                    <option value="urgent">Urgent Action</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl">
                                Send Broadcast →
                            </button>
                        </form>
                    </div>
                </div>

                {{-- System Activity --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-[3rem] p-10 border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-6 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">System activity</h3>
                        </div>
                        <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl uppercase tracking-widest animate-pulse">Live feed</span>
                    </div>
                    
                    <div class="space-y-6">
                        @foreach($institutes->sortByDesc('created_at')->take(4) as $inst)
                        <div class="flex items-center gap-6 p-4 rounded-3xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">New Institute Registered: <span class="text-indigo-600">{{ $inst->name }}</span></p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Verification Pending · {{ $inst->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach

                        <div class="flex items-center gap-6 p-4 rounded-3xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Global Revenue Sync: <span class="text-emerald-600">₹{{ number_format($stats['total_revenue']) }}</span></p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Automated audit completed successfully</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Platform Growth Chart --}}
            <div class="bg-gray-950 rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px]"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-12">
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-tighter italic">Platform Expansion</h3>
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mt-1">Growth over last 6 months</p>
                        </div>
                    </div>
                    <div id="growthChart"></div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'New Institutes',
                    data: {!! json_encode($growth['data']) !!}
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: { show: false },
                    foreColor: '#6366f1'
                },
                colors: ['#6366f1'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 4 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.05,
                        stops: [20, 100, 100, 100]
                    }
                },
                grid: { borderColor: 'rgba(255,255,255,0.05)', strokeDashArray: 4 },
                xaxis: {
                    categories: {!! json_encode($growth['labels']) !!},
                    labels: { style: { fontWeight: 900 } }
                },
                yaxis: { show: false },
                tooltip: { theme: 'dark' }
            };

            var chart = new ApexCharts(document.querySelector("#growthChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>
