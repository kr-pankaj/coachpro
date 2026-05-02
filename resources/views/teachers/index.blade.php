<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Staff & Teachers') }}
            </h2>
            <a href="{{ route('teachers.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Add Teacher
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
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
                            <th>Faculty Profile</th>
                            <th>Communication</th>
                            <th>Tenure Since</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 font-black flex items-center justify-center">
                                            {{ substr($teacher->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $teacher->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Verified Staff</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs font-bold text-gray-500">
                                        {{ $teacher->email }}
                                    </span>
                                </td>
                                <td class="text-xs font-bold text-gray-400 italic">
                                    {{ $teacher->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all" title="Edit Teacher">
                                            <x-icons.edit class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this teacher?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all" title="Remove Teacher">
                                                <x-icons.delete class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <p class="text-sm text-gray-400 font-bold italic">No faculty members registered yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
