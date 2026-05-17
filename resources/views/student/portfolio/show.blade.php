<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->name }}'s Academic Portfolio | {{ $student->institute->name }}</title>
    
    <!-- Social Sharing Meta Tags -->
    <meta name="description" content="Check out my academic achievements, badges, and skills at {{ $student->institute->name }}! Currently Level {{ $student->level }} with {{ number_format($student->xp_total) }} XP.">
    <meta property="og:title" content="{{ $student->name }}'s Academic Portfolio">
    <meta property="og:description" content="Level {{ $student->level }} Student at {{ $student->institute->name }}. Explore my skills and achievements!">
    <meta property="og:image" content="{{ $student->photo_url ?: asset('images/default-avatar.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="profile">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $student->name }}'s Academic Portfolio">
    <meta name="twitter:description" content="Currently Level {{ $student->level }} with {{ number_format($student->xp_total) }} XP at {{ $student->institute->name }}.">
    <meta name="twitter:image" content="{{ $student->photo_url ?: asset('images/default-avatar.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .quonix-gradient { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-indigo-100 antialiased">
    
    {{-- Decorative Background --}}
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-24 w-64 h-64 bg-fuchsia-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-12">
        
        {{-- Profile Header --}}
        <div class="glass rounded-[3rem] p-10 shadow-2xl shadow-indigo-500/5 mb-8">
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="relative group">
                    <div class="absolute inset-0 quonix-gradient rounded-[2.5rem] rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-20"></div>
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}" class="w-40 h-40 rounded-[2.5rem] object-cover shadow-2xl relative z-10 border-4 border-white" alt="{{ $student->name }}">
                    @else
                        <div class="w-40 h-40 rounded-[2.5rem] quonix-gradient flex items-center justify-center text-white text-6xl font-black shadow-2xl relative z-10 border-4 border-white">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute -bottom-4 -right-4 w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-xl z-20">
                        🔥
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-4">
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-100">
                            Level {{ $student->level }} Student
                        </span>
                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                            {{ $student->institute->name }}
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-2">
                        {{ $student->name }}
                    </h1>
                    <p class="text-slate-500 font-medium max-w-lg mb-6">
                        {{ $student->bio ?? 'Aspiring professional at ' . $student->institute->name . '. Ranked #' . $stats['rank'] . ' globally.' }}
                    </p>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-8">
                        @if($student->github_url)
                        <a href="{{ $student->github_url }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                            GitHub
                        </a>
                        @endif
                        @if($student->linkedin_url)
                        <a href="{{ $student->linkedin_url }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-[#0077b5] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            LinkedIn
                        </a>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-center md:justify-start gap-3 mt-8">
                        @foreach($student->badges as $badge)
                            <div class="group relative">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center text-xl hover:scale-110 transition-all cursor-pointer border border-slate-100">
                                    {{ $badge->icon }}
                                </div>
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 px-3 py-1 bg-slate-900 text-white text-[9px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-black uppercase">
                                    {{ $badge->name }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Stats Cards --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="glass rounded-[2rem] p-8 shadow-xl shadow-slate-200/50">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Learning Analytics</h3>
                    
                    <div class="space-y-6">
                        @if($student->show_attendance_on_portfolio)
                        <div>
                            <div class="flex justify-between text-sm font-black mb-2">
                                <span>Attendance</span>
                                <span class="text-emerald-600">{{ $stats['attendance_rate'] }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stats['attendance_rate'] }}%"></div>
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <div class="flex justify-between text-sm font-black mb-2">
                                <span>Assessment Avg.</span>
                                <span class="text-indigo-600">{{ round($stats['avg_score']) }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ round($stats['avg_score']) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-[10px] font-black mb-2">
                                <span>Global Rank</span>
                                <span class="text-rose-600">#{{ $stats['rank'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="quonix-gradient rounded-[2rem] p-8 text-white shadow-2xl shadow-indigo-500/20">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-70">Experience</h3>
                        <div class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-black">TOP 5%</div>
                    </div>
                    <div class="text-4xl font-black mb-2">{{ number_format($student->xp_total) }}</div>
                    <p class="text-xs font-bold opacity-80 mb-6">Total XP Earned</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-lg">🔥</div>
                            <div>
                                <div class="text-xs font-black">{{ $student->current_streak }} Days</div>
                                <div class="text-[8px] font-bold opacity-60 uppercase">Current Streak</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Skills & Expertise --}}
                <div class="glass rounded-[2.5rem] p-10 shadow-xl shadow-slate-200/50">
                    <h3 class="text-xl font-black tracking-tight mb-8">Technical Expertise</h3>
                    
                    <div class="flex flex-wrap gap-3">
                        @forelse($student->skills ?? [] as $skill)
                            <span class="px-6 py-3 bg-white border border-slate-100 rounded-2xl text-xs font-black text-slate-700 shadow-sm hover:border-indigo-200 hover:text-indigo-600 transition-all cursor-default">
                                {{ $skill }}
                            </span>
                        @empty
                            <p class="text-slate-400 italic text-sm">No specific skills listed yet.</p>
                        @endforelse
                    </div>

                    @if($skills->count())
                    <div class="mt-12 pt-8 border-t border-slate-50">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Academic Proficiency (via Assessments)</h4>
                        <div class="space-y-6">
                            @foreach($skills as $skill)
                            <div class="group">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-black text-slate-600">{{ $skill['name'] }}</span>
                                    <span class="text-[10px] font-black text-slate-400">{{ $skill['score'] }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-50 rounded-full overflow-hidden">
                                    <div class="h-full quonix-gradient rounded-full" style="width: {{ $skill['score'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Notable Achievements --}}
                @if(is_array($student->notable_achievements) && count($student->notable_achievements))
                <div class="glass rounded-[2.5rem] p-10 shadow-xl shadow-slate-200/50">
                    <h3 class="text-xl font-black tracking-tight mb-8">Notable Achievements</h3>
                    <div class="space-y-4">
                        @foreach($student->notable_achievements as $achievement)
                        <div class="flex items-start gap-4 p-5 rounded-3xl bg-emerald-50/30 border border-emerald-100/50">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            </div>
                            <p class="text-sm font-black text-slate-800 leading-relaxed">{{ $achievement }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Featured Projects --}}
                @if(is_array($student->projects) && count($student->projects))
                <div class="glass rounded-[2.5rem] p-10 shadow-xl shadow-slate-200/50">
                    <h3 class="text-xl font-black tracking-tight mb-8">Featured Projects</h3>
                    <div class="space-y-8">
                        @foreach($student->projects as $project)
                        <div class="group p-8 rounded-[2rem] bg-slate-50/50 border border-slate-100 hover:border-indigo-200 transition-all">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4">
                                <div>
                                    <h4 class="text-lg font-black text-slate-800">{{ $project['title'] }}</h4>
                                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">{{ $project['tech'] }}</p>
                                </div>
                                @if($project['link'])
                                <a href="{{ $project['link'] }}" target="_blank" class="px-5 py-2 bg-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-slate-100 hover:bg-slate-900 hover:text-white transition-all">
                                    View Project
                                </a>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed font-medium">
                                {{ $project['description'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Recent Achievements --}}
                <div class="glass rounded-[2.5rem] p-10 shadow-xl shadow-slate-200/50">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black tracking-tight">Recent Achievements</h3>
                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Last 30 Days</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($student->attempts->take(4) as $attempt)
                        <div class="flex items-center gap-4 p-5 rounded-3xl bg-slate-50/50 border border-slate-100 hover:border-indigo-200 transition-all group">
                            <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center shadow-sm text-indigo-500 group-hover:scale-110 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-800">{{ $attempt->quiz->title }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Completed: {{ $attempt->completed_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- CTA / Footer --}}
                <div class="text-center pt-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Powered by QuonixAI Academy</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        {{-- Add to LinkedIn Certification --}}
                        <a href="https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name={{ urlencode($student->batches->first()?->name ?? 'Professional Certificate') }}&organizationName={{ urlencode($student->institute->name) }}&issueYear={{ date('Y') }}&issueMonth={{ date('n') }}&certUrl={{ urlencode(url()->current()) }}" target="_blank" class="px-8 py-3 bg-[#0077b5] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            Add to LinkedIn
                        </a>

                        {{-- WhatsApp Share --}}
                        <a href="https://wa.me/?text=Check out my academic portfolio at {{ $student->institute->name }}! {{ urlencode(url()->current()) }}" target="_blank" class="px-8 py-3 bg-emerald-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>

                        {{-- LinkedIn Share --}}
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="px-8 py-3 bg-[#0077b5] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            LinkedIn
                        </a>

                        {{-- Copy Link --}}
                        <button onclick="copyPortfolioLink()" class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyPortfolioLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = event.currentTarget;
                const originalText = btn.innerHTML;
                btn.innerHTML = 'Link Copied!';
                btn.classList.add('bg-emerald-600');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-emerald-600');
                }, 2000);
            });
        }
    </script>
</body>
</html>
