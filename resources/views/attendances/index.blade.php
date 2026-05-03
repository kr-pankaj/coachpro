<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Premium Filter Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('attendances.index') }}" class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Select Batch</label>
                        <select id="batch_id" name="batch_id" class="block w-full !rounded-2xl !py-3 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                            <option value="">-- Choose Batch --</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ $batch_id == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-64 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Attendance Date</label>
                        <x-text-input id="date" class="block w-full !rounded-2xl !py-3" type="date" name="date" :value="$date" required />
                    </div>
                    <div class="w-full md:w-auto">
                        <button type="submit" class="btn-gradient-indigo w-full px-8 py-4">
                            Fetch Students
                        </button>
                    </div>
                </form>
            </div>

            @if($batch_id && $students->count() > 0)
                @if (session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-bold flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('attendances.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ $batch_id }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div>
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Marking Attendance</h3>
                            <p class="text-xs text-gray-400 font-bold mt-1">Session Date: <span class="text-indigo-600 dark:text-indigo-400">{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</span></p>
                        </div>
                        <button type="button" onclick="markAllPresent()" class="px-6 py-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all border border-indigo-100 dark:border-indigo-800/30">
                            Quick Mark: All Present
                        </button>
                    </div>
                    
                    <div class="table-container overflow-x-auto">
                        <table class="table-premium">
                            <thead>
                                <tr>
                                    <th>Student Profile</th>
                                    <th>Attendance Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    @php
                                        $studentId = (string) $student->id;
                                        $isMarked = array_key_exists($studentId, $attendances);
                                        $currentStatus = $isMarked ? $attendances[$studentId] : 'present';
                                    @endphp
                                    <tr class="group">
                                        <td>
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-400 font-black flex items-center justify-center border border-gray-100 dark:border-gray-700 group-hover:border-indigo-200 transition-colors">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $student->name }}</p>
                                                    @if(!$isMarked)
                                                        <span class="text-[10px] text-amber-500 font-black uppercase tracking-tighter">● Not Marked Today</span>
                                                    @else
                                                        <span class="text-[10px] text-emerald-500 font-black uppercase tracking-tighter">● Updated</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-6">
                                                <label class="flex items-center cursor-pointer group/label">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="sr-only peer" {{ (!$isMarked || $currentStatus == 'present') ? 'checked' : '' }}>
                                                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 dark:border-gray-700 text-gray-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 peer-checked:border-emerald-200 dark:peer-checked:bg-emerald-900/20 dark:peer-checked:border-emerald-800 transition-all">Present</span>
                                                </label>
                                                <label class="flex items-center cursor-pointer group/label">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="sr-only peer" {{ ($isMarked && $currentStatus == 'absent') ? 'checked' : '' }}>
                                                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 dark:border-gray-700 text-gray-400 peer-checked:bg-rose-50 peer-checked:text-rose-600 peer-checked:border-rose-200 dark:peer-checked:bg-rose-900/20 dark:peer-checked:border-rose-800 transition-all">Absent</span>
                                                </label>
                                                <label class="flex items-center cursor-pointer group/label">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="sr-only peer" {{ ($isMarked && $currentStatus == 'late') ? 'checked' : '' }}>
                                                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 dark:border-gray-700 text-gray-400 peer-checked:bg-amber-50 peer-checked:text-amber-600 peer-checked:border-amber-200 dark:peer-checked:bg-amber-900/20 dark:peer-checked:border-amber-800 transition-all">Late</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="btn-gradient-indigo px-12 py-4">
                            Save Attendance Records
                        </button>
                    </div>
                </form>

            @elseif($batch_id && $students->count() == 0)
                <div class="p-12 text-center bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/20 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">No Students Found</h3>
                    <p class="text-sm text-gray-400 font-bold mt-1">This batch doesn't have any students enrolled yet.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(document.getElementById('batch_id')) {
                new TomSelect('#batch_id', {
                    create: false,
                    placeholder: "Search for a batch...",
                    dropdownParent: 'body'
                });
            }
        });

        function markAllPresent() {
            const radios = document.querySelectorAll('input[type="radio"][value="present"]');
            radios.forEach(radio => {
                radio.checked = true;
                // Trigger change event if needed for reactive UI
                radio.dispatchEvent(new Event('change'));
            });
        }
    </script>

</x-app-layout>
