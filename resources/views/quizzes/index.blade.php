<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Quizzes & Tests</h2>
            <a href="{{ route('quizzes.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Quiz
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Premium Filter Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('quizzes.index') }}" class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Quiz Search</label>
                        <x-text-input id="search" name="search" type="text" class="block w-full !rounded-2xl !py-3" placeholder="Search by quiz title..." :value="request('search')" />
                    </div>
                    <div class="w-full md:w-72 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Target Batch</label>
                        <select id="batch_id" name="batch_id" class="block w-full">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit" class="btn-gradient-indigo flex-1 md:flex-none px-8">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'batch_id']))
                            <a href="{{ route('quizzes.index') }}" class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-gray-100 transition-all border border-gray-100 dark:border-gray-600">
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

            {{-- Premium Quiz Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($quizzes as $quiz)
                    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl shadow-gray-100/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden hover:border-indigo-200 dark:hover:border-indigo-800/50 transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-6">
                                <span class="badge-premium {{ $quiz->is_active ? 'badge-emerald' : 'badge-rose' }}">
                                    <span class="w-1 h-1 rounded-full bg-current mr-1.5 {{ $quiz->is_active ? 'animate-pulse' : '' }}"></span>
                                    {{ $quiz->is_active ? 'Active' : 'Closed' }}
                                </span>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $quiz->questions_count }} Qs</span>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight mb-2 group-hover:text-indigo-600 transition-colors">
                                {{ $quiz->title }}
                            </h3>
                            
                            <div class="flex flex-wrap gap-3 mb-6">
                                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                    {{ $quiz->batch ? $quiz->batch->name : 'Public Exam' }}
                                </span>
                                <span class="px-3 py-1 bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                    ⏱ {{ $quiz->time_limit_minutes }}m Limit
                                </span>
                            </div>

                            <div class="pt-6 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
                                <a href="{{ route('quizzes.show', $quiz) }}" class="flex items-center gap-2 text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:gap-3 transition-all">
                                    Review Performance
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                                </a>
                                
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('quizzes.edit', $quiz) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all" title="Edit Quiz">
                                        <x-icons.edit class="w-4 h-4" />
                                    </a>
                                    <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Archive this assessment?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all" title="Delete Quiz">
                                            <x-icons.delete class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white dark:bg-gray-800 rounded-[2.5rem] border-2 border-dashed border-gray-100 dark:border-gray-700">
                        <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-900/20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-indigo-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white">Ready for Assessments?</h3>
                        <p class="text-sm text-gray-400 font-bold mt-1">Create your first interactive quiz to start tracking progress.</p>
                        <a href="{{ route('quizzes.create') }}" class="inline-flex mt-8 btn-gradient-indigo px-8 py-4">
                            New Quiz Panel
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $quizzes->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(document.getElementById('batch_id')) {
                new TomSelect('#batch_id', {
                    create: false,
                    placeholder: "Filter by target batch...",
                    dropdownParent: 'body'
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#batch_id', {
                create: false,
                placeholder: "Filter by batch...",
                dropdownParent: 'body'
            });
        });
    </script>
</x-app-layout>
