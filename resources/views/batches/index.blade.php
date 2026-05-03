<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Batches') }}
            </h2>
            <a href="{{ route('batches.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Add Batch
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Premium Filter Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('batches.index') }}" class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Search Batches</label>
                        <x-text-input id="search" name="search" type="text" class="block w-full !rounded-2xl !py-3" placeholder="Batch name, subject or slot..." :value="request('search')" />
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit" class="btn-gradient-indigo flex-1 md:flex-none px-8">
                            Search
                        </button>
                        @if(request()->filled('search'))
                            <a href="{{ route('batches.index') }}" class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-gray-100 transition-all border border-gray-100 dark:border-gray-600">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Premium Table --}}
            <div class="table-container overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Batch Details</th>
                            <th>Academic Subject</th>
                            <th>Schedule Slot</th>
                            <th>Status</th>
                            <th>Enrolled</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($batches as $batch)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 font-black flex items-center justify-center">
                                            {{ substr($batch->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $batch->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $batch->subject }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-premium badge-amber">
                                        {{ $batch->subject }}
                                    </span>
                                </td>
                                <td class="text-xs font-black text-gray-500 uppercase">
                                    {{ $batch->time_slot }}
                                </td>
                                <td>
                                    @if($batch->is_active)
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-500 border border-gray-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-xs font-black text-indigo-600">
                                            {{ $batch->students_count }}
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Students</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('batches.edit', $batch) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all" title="Edit Batch">
                                            <x-icons.edit class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('batches.destroy', $batch) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all" title="Delete Batch">
                                                <x-icons.delete class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <p class="text-sm text-gray-400 font-bold italic">No batches found matching your search.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
