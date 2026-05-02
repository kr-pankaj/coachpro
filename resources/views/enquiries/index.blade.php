<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Leads & Enquiries') }}
            </h2>
            <a href="{{ route('enquiries.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Lead
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Premium Filter Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('enquiries.index') }}" class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Lead Search</label>
                        <x-text-input id="search" name="search" type="text" class="block w-full !rounded-2xl !py-3" placeholder="Name, Phone or Course..." :value="request('search')" />
                    </div>
                    <div class="w-full md:w-64 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Pipeline Stage</label>
                        <select id="status" name="status" class="block w-full">
                            <option value="">All Pipeline Stages</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New Inquiry</option>
                            <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="demo_scheduled" {{ request('status') == 'demo_scheduled' ? 'selected' : '' }}>Demo Scheduled</option>
                            <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted Student</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost Lead</option>
                        </select>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit" class="btn-gradient-indigo flex-1 md:flex-none px-8">
                            Search Pipeline
                        </button>
                        @if(request()->anyFilled(['search', 'status']))
                            <a href="{{ route('enquiries.index') }}" class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-gray-100 transition-all border border-gray-100 dark:border-gray-600">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Premium Lead Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($enquiries as $enquiry)
                    @php
                        $statusStyles = [
                            'new' => 'badge-indigo',
                            'contacted' => 'badge-amber',
                            'demo_scheduled' => 'badge-emerald',
                            'converted' => 'badge-emerald',
                            'lost' => 'badge-rose',
                        ];
                        $statusStyle = $statusStyles[$enquiry->status] ?? 'badge-indigo';
                        $waMsg = urlencode("Hi {$enquiry->student_name}, this is regarding your inquiry at " . auth()->user()->institute->name . ".");
                    @endphp

                    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl shadow-gray-100/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden hover:border-indigo-200 dark:hover:border-indigo-800/50 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span class="badge-premium {{ $statusStyle }}">
                                    <span class="w-1 h-1 rounded-full bg-current mr-1.5 animate-pulse"></span>
                                    {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                                </span>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openFollowUpModal({{ $enquiry->id }})" class="p-1.5 text-violet-500 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-lg transition-all" title="AI Assist ✨">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                    </button>
                                    <a href="{{ route('enquiries.edit', $enquiry) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                        <x-icons.edit class="w-4 h-4" />
                                    </a>
                                </div>
                            </div>

                            <h3 class="text-base font-black text-gray-900 dark:text-white leading-tight mb-4">{{ $enquiry->student_name }}</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-xs text-gray-500 font-bold">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                    </div>
                                    <span class="truncate">{{ $enquiry->course_interested ?? 'General Inquiry' }}</span>
                                </div>

                                @if($enquiry->phone)
                                <div class="flex items-center gap-3 text-xs text-gray-400 font-bold">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                    </div>
                                    <span>{{ $enquiry->phone }}</span>
                                </div>
                                @endif

                                @if($enquiry->next_follow_up_date)
                                    @php 
                                        $isOverdue = $enquiry->next_follow_up_date->isPast() && !$enquiry->next_follow_up_date->isToday() && !in_array($enquiry->status, ['converted','lost']);
                                        $isToday = $enquiry->next_follow_up_date->isToday();
                                    @endphp
                                    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest {{ $isOverdue ? 'text-rose-500' : ($isToday ? 'text-amber-500' : 'text-indigo-400') }}">
                                        <div class="w-8 h-8 rounded-lg bg-current/5 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                        </div>
                                        <span>{{ $isToday ? 'Due Today' : $enquiry->next_follow_up_date->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 divide-x divide-gray-50 dark:divide-gray-700/50 bg-gray-50/50 dark:bg-gray-800/50 border-t border-gray-50 dark:border-gray-700/50">
                            <a href="tel:{{ $enquiry->phone }}" class="flex items-center justify-center gap-2 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 hover:bg-white dark:hover:bg-gray-700 hover:text-indigo-600 transition-all">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                Call
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $enquiry->phone) }}?text={{ $waMsg }}" target="_blank" class="flex items-center justify-center gap-2 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.487-1.761-1.66-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                Chat
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white dark:bg-gray-800 rounded-[2.5rem] border-2 border-dashed border-gray-100 dark:border-gray-700">
                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white">Pipeline Empty</h3>
                        <p class="text-sm text-gray-400 font-bold mt-1">Start adding leads to build your enrollment funnel.</p>
                        <a href="{{ route('enquiries.create') }}" class="inline-flex mt-8 btn-gradient-indigo px-8 py-4">
                            Add New Prospect
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $enquiries->links() }}
            </div>
        </div>
    </div>

    <!-- AI Follow-up Modal -->
    <div id="aiFollowUpModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500/75 backdrop-blur-sm" onclick="closeFollowUpModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">AI Smart Follow-up</h3>
                    </div>
                    <button type="button" onclick="closeFollowUpModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div id="aiLoading" class="py-12 text-center">
                    <div class="inline-block w-8 h-8 border-4 border-violet-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="mt-4 text-sm font-bold text-gray-500 italic">Gemini is crafting a personalized message...</p>
                </div>

                <div id="aiContent" class="hidden space-y-6">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <p id="aiSuggestion" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap"></p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="copyToClipboard()" class="flex-1 py-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl font-black text-[10px] uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            Copy Message
                        </button>
                        <a id="waBtn" target="_blank" class="flex-1 py-4 bg-emerald-500 hover:bg-emerald-600 rounded-2xl font-black text-[10px] uppercase tracking-widest text-white shadow-lg shadow-emerald-100 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .01 5.403.006 12.039a11.81 11.81 0 001.578 5.925L0 24l6.135-1.612a11.771 11.771 0 005.911 1.577h.005c6.637 0 12.042-5.405 12.046-12.041a11.82 11.82 0 00-3.533-8.527"/></svg>
                            WhatsApp Suggestion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function openFollowUpModal(enquiryId) {
        const modal = document.getElementById('aiFollowUpModal');
        const loading = document.getElementById('aiLoading');
        const content = document.getElementById('aiContent');
        const suggestion = document.getElementById('aiSuggestion');
        const waBtn = document.getElementById('waBtn');

        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        content.classList.add('hidden');

        fetch(`/ai/enquiries/${enquiryId}/followup`)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    suggestion.innerText = data.suggestion;
                    
                    const cleanPhone = data.phone.replace(/[^0-9]/g, '');
                    const waPhone = cleanPhone.length === 10 ? '91' + cleanPhone : cleanPhone;
                    waBtn.href = `https://wa.me/${waPhone}?text=${encodeURIComponent(data.suggestion)}`;
                    
                    loading.classList.add('hidden');
                    content.classList.remove('hidden');
                } else {
                    alert('AI Error: ' + data.message);
                    closeFollowUpModal();
                }
            })
            .catch(err => {
                alert('Error connecting to AI service.');
                closeFollowUpModal();
            });
    }

    function closeFollowUpModal() {
        document.getElementById('aiFollowUpModal').classList.add('hidden');
    }

    function copyToClipboard() {
        const text = document.getElementById('aiSuggestion').innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        });
    }
    </script>
</x-app-layout>
