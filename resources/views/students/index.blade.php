<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Students') }}
            </h2>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Add Student
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Premium Filter Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('students.index') }}" class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Search Student</label>
                        <x-text-input id="search" name="search" type="text" class="block w-full !rounded-2xl !py-3" placeholder="Name, Email or Phone..." :value="request('search')" />
                    </div>
                    <div class="w-full md:w-72 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Batch Filter</label>
                        <select id="batch_id" name="batch_id" class="block w-full">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit" class="btn-gradient-indigo flex-1 md:flex-none">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'batch_id']))
                            <a href="{{ route('students.index') }}" class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-gray-100 transition-all border border-gray-100 dark:border-gray-600">
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
                            <th>Student Details</th>
                            <th>Phone</th>
                            <th>Enrolled Batches</th>
                            <th>Joining Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 font-black flex items-center justify-center">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $student->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $student->email ?? 'No Email' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs font-black text-gray-500">
                                    @if($student->phone)
                                        <div class="flex items-center gap-2">
                                            <span>{{ $student->phone }}</span>
                                            @php
                                                $cleanPhone = preg_replace('/[^0-9]/', '', $student->phone);
                                                if(strlen($cleanPhone) == 10) $cleanPhone = "91" . $cleanPhone;
                                                $waMsg = urlencode("Welcome to " . auth()->user()->institute->name . "! We are excited to have you with us.");
                                            @endphp
                                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" class="text-emerald-500 hover:text-emerald-600 transition-colors" title="Chat on WhatsApp">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .01 5.403.006 12.039a11.81 11.81 0 001.578 5.925L0 24l6.135-1.612a11.771 11.771 0 005.911 1.577h.005c6.637 0 12.042-5.405 12.046-12.041a11.82 11.82 0 00-3.533-8.527"/></svg>
                                            </a>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($student->batches as $batch)
                                            <span class="badge-premium badge-indigo">
                                                {{ $batch->name }}
                                            </span>
                                        @empty
                                            <span class="text-[10px] text-gray-400 font-bold italic">No Batch</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="text-xs font-bold text-gray-400 italic">
                                    {{ \Carbon\Carbon::parse($student->joined_date)->format('M d, Y') }}
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('students.edit', $student) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all" title="Edit Student">
                                            <x-icons.edit class="w-4 h-4" />
                                        </a>
                                        <a href="{{ route('students.id-card', $student) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all" title="Generate ID Card">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </a>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all" title="Delete Student">
                                                <x-icons.delete class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <p class="text-sm text-gray-400 font-bold italic">No students found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $students->links() }}
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
