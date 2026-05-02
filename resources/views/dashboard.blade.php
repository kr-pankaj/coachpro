<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">{{ session('success') }}</div>
            @endif

            {{-- Profile Completion Warning --}}
            @if($profilePct < 70)
            <div class="bg-white rounded-2xl shadow-sm border border-amber-200 p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 mb-1">Complete your institute profile <span class="text-amber-600">({{ $profilePct }}% done)</span></p>
                    <p class="text-sm text-gray-500">Students see your institute info on the registration page. Add your logo, description, address and contact details.</p>
                    <div class="mt-2 h-2 bg-gray-100 rounded-full overflow-hidden w-full max-w-xs">
                        <div class="h-full bg-amber-400 rounded-full" style="width:{{ $profilePct }}%"></div>
                    </div>
                </div>
                <a href="{{ route('institute.settings') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white shrink-0" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                    Complete Profile →
                </a>
            </div>
            @endif

            {{-- KPI Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                $cards = [
                    ['label'=>'Total Students','value'=>$totalStudents,'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0','color'=>'#4f46e5','bg'=>'#ede9fe','suffix'=>''],
                    ['label'=>'Batches','value'=>$totalBatches,'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10','color'=>'#0891b2','bg'=>'#e0f2fe','suffix'=>''],
                    ['label'=>"Today's Attendance",'value'=>$todayTotal > 0 ? round(($todayAttended/$todayTotal)*100).'%' : 'N/A','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4','color'=>'#059669','bg'=>'#d1fae5','suffix'=>$todayAttended.'/'.$todayTotal],
                    ['label'=>'Pending Fees','value'=>'₹'.number_format($pendingFees),'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z','color'=>'#dc2626','bg'=>'#fee2e2','suffix'=>'Collected: ₹'.number_format($collectedFees)],
                ];
                @endphp
                @foreach($cards as $card)
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:{{ $card['bg'] }}">
                            <svg width="20" height="20" fill="none" stroke="{{ $card['color'] }}" stroke-width="1.75" viewBox="0 0 24 24"><path d="{{ $card['icon'] }}" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
                    @if($card['suffix'])
                        <p class="text-xs font-medium mt-1" style="color:{{ $card['color'] }}">{{ $card['suffix'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Quick Actions</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round"/></svg>
                        Add Student
                    </a>
                    <a href="{{ route('attendances.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:linear-gradient(135deg,#059669,#10b981);">
                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round"/></svg>
                        Mark Attendance
                    </a>
                    <a href="{{ route('fees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:linear-gradient(135deg,#0891b2,#0ea5e9);">
                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round"/></svg>
                        Record Fee
                    </a>
                    <a href="{{ route('batches.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round"/></svg>
                        New Batch
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Recent Students --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <p class="font-semibold text-gray-800">Recent Students</p>
                        <a href="{{ route('students.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View all →</a>
                    </div>
                    @forelse($recentStudents as $s)
                    <div class="flex items-center gap-3 py-2.5 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                            {{ strtoupper(substr($s->name,0,1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $s->name }}</p>
                            <p class="text-xs text-gray-400">{{ $s->batch?->name ?? 'No batch' }} · Joined {{ $s->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ ($s->status ?? 'active') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ ucfirst($s->status ?? 'active') }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto mb-2 text-gray-300" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" stroke-linecap="round"/></svg>
                        <p class="text-sm text-gray-400">No students yet.</p>
                        <a href="{{ route('students.create') }}" class="text-xs text-indigo-600 font-semibold">Add your first student →</a>
                    </div>
                    @endforelse
                </div>

                {{-- Batches & Announcements --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="font-semibold text-gray-800">Batches</p>
                            <a href="{{ route('batches.index') }}" class="text-xs font-semibold text-indigo-600">View all →</a>
                        </div>
                        @forelse($batches as $b)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $b->name }}</p>
                                <p class="text-xs text-gray-400">{{ $b->time_slot ?? 'No time set' }}</p>
                            </div>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $b->students_count }} students</span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-4">No batches yet.</p>
                        @endforelse
                    </div>

                    {{-- Announcements --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <p class="font-semibold text-gray-800 mb-3">Post Announcement</p>
                        <form method="POST" action="{{ route('announcements.store') }}" class="space-y-2">
                            @csrf
                            <input type="text" name="title" placeholder="Title" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400">
                            <textarea name="content" placeholder="Write your notice..." rows="2" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none"></textarea>
                            <div class="flex gap-2">
                                <select name="type" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 flex-1 focus:outline-none">
                                    <option value="info">ℹ Info</option>
                                    <option value="warning">⚠ Warning</option>
                                    <option value="success">✅ Success</option>
                                </select>
                                <input type="date" name="expires_on" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 flex-1 focus:outline-none" title="Expires on (optional)">
                            </div>
                            <button type="submit" class="w-full py-2 text-sm font-semibold text-white rounded-lg" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">Post Notice</button>
                        </form>

                        @if($announcements->count())
                        <div class="mt-4 space-y-2">
                            @foreach($announcements as $ann)
                            <div class="flex items-start gap-2 p-2.5 rounded-lg {{ $ann->type === 'warning' ? 'bg-amber-50' : ($ann->type === 'success' ? 'bg-green-50' : 'bg-blue-50') }}">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-700">{{ $ann->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($ann->content, 60) }}</p>
                                </div>
                                <form method="POST" action="{{ route('announcements.destroy', $ann) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-300 hover:text-red-500 text-xs">✕</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>


</x-app-layout>
