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

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">{{ session('success') }}</div>
            @endif

            <form method="GET" action="{{ route('quizzes.index') }}" class="flex flex-col md:flex-row gap-4 mb-8">
                <div class="flex-1">
                    <x-text-input id="search" name="search" type="text" class="block w-full" placeholder="Search quiz title..." :value="request('search')" />
                </div>
                <div class="w-full md:w-64">
                    <select id="batch_id" name="batch_id" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <x-primary-button type="submit">
                        {{ __('Filter') }}
                    </x-primary-button>
                    @if(request()->anyFilled(['search', 'batch_id']))
                        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                            {{ __('Clear') }}
                        </a>
                    @endif
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($quizzes as $quiz)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $quiz->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $quiz->questions_count }} Questions</span>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mt-2 mb-1">{{ $quiz->title }}</h3>
                            @if($quiz->batch)
                                <p class="text-xs text-indigo-600 font-medium">{{ $quiz->batch->name }}</p>
                            @else
                                <p class="text-xs text-gray-400">All Batches</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">⏱ {{ $quiz->time_limit_minutes }} min time limit</p>
                        </div>
                        <div class="grid grid-cols-2 divide-x divide-gray-100 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('quizzes.show', $quiz) }}" class="flex items-center justify-center gap-1.5 py-3 text-sm font-semibold text-indigo-600 hover:bg-indigo-50 transition-colors">View Results</a>
                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete this quiz?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full h-full flex items-center justify-center gap-1.5 py-3 text-sm font-semibold text-red-500 hover:bg-red-50 transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <p class="text-gray-500 font-medium">No quizzes yet. Create your first one!</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $quizzes->links() }}
            </div>
        </div>
        </div>
    </div>
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
