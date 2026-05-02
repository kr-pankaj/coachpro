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

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('enquiries.index') }}" class="flex flex-col md:flex-row gap-4 mb-8">
                <div class="flex-1">
                    <x-text-input id="search" name="search" type="text" class="block w-full border-gray-100 shadow-sm" placeholder="Search by name, phone or course..." :value="request('search')" />
                </div>
                <div class="w-full md:w-64">
                    <select id="status" name="status" class="block w-full border-gray-100 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 rounded-xl shadow-sm">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="demo_scheduled" {{ request('status') == 'demo_scheduled' ? 'selected' : '' }}>Demo Scheduled</option>
                        <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('enquiries.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($enquiries as $enquiry)
                    @php
                        $statusColors = [
                            'new' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'New'],
                            'contacted' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'label' => 'Contacted'],
                            'demo_scheduled' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Demo Scheduled'],
                            'converted' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Converted'],
                            'lost' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Lost'],
                        ];
                        $color = $statusColors[$enquiry->status];
                        
                        // WhatsApp Message Template
                        $waMsg = urlencode("Hi {$enquiry->student_name}, this is regarding your inquiry at " . auth()->user()->institute->name . ".");
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider rounded-full {{ $color['bg'] }} {{ $color['text'] }}">
                                    {{ $color['label'] }}
                                </span>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-gray-100 z-10 py-1" style="display:none;">
                                        <a href="{{ route('enquiries.edit', $enquiry) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                        <form action="{{ route('enquiries.destroy', $enquiry) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50" onclick="return confirm('Delete this lead?');">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $enquiry->student_name }}</h3>
                            
                            <div class="space-y-1.5 mt-3 mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <span class="truncate">{{ $enquiry->course_interested ?? 'General Inquiry' }}</span>
                                </p>
                                @if($enquiry->next_follow_up_date)
                                    @php 
                                        $isOverdue = $enquiry->next_follow_up_date->isPast() && !$enquiry->next_follow_up_date->isToday() && !in_array($enquiry->status, ['converted','lost']);
                                        $isToday = $enquiry->next_follow_up_date->isToday();
                                    @endphp
                                    <p class="text-sm flex items-center gap-2 {{ $isOverdue ? 'text-red-600 font-semibold' : ($isToday ? 'text-amber-600 font-semibold' : 'text-gray-600') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Follow up: {{ $isToday ? 'Today' : $enquiry->next_follow_up_date->format('M d, Y') }}
                                        @if($isOverdue) (Overdue) @endif
                                    </p>
                                @endif
                            </div>

                        </div>
                        
                        <!-- Quick Actions Footer -->
                        <div class="grid grid-cols-2 divide-x divide-gray-100 dark:divide-gray-700 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
                            <a href="tel:{{ $enquiry->phone }}" class="flex items-center justify-center gap-2 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                Call
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $enquiry->phone) }}?text={{ $waMsg }}" target="_blank" class="flex items-center justify-center gap-2 py-3 text-sm font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.487-1.761-1.66-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                WhatsApp
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 border-dashed">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">No leads yet</h3>
                        <p class="text-gray-500 mb-4">Add your first prospective student to start tracking.</p>
                        <a href="{{ route('enquiries.create') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                            Add New Lead →
                        </a>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#status', {
                create: false,
                placeholder: "Filter by status...",
                dropdownParent: 'body'
            });
        });
    </script>
</x-app-layout>
