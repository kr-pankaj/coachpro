<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('attendances.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="w-full sm:w-1/3">
                            <x-input-label for="batch_id" :value="__('Select Batch')" />
                            <select id="batch_id" name="batch_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Choose --</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ $batch_id == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-1/3">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="$date" required />
                        </div>
                        <div class="w-full sm:w-auto">
                            <x-primary-button>
                                {{ __('Fetch Students') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if($batch_id && $students->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('attendances.store') }}">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{ $batch_id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($students as $student)
                                        @php
                                            $currentStatus = $attendances[$student->id] ?? 'present';
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $student->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex items-center space-x-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="text-green-600 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                                        <span class="ml-2 text-green-600 dark:text-green-400 font-semibold">Present</span>
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="text-red-600 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                                        <span class="ml-2 text-red-600 dark:text-red-400 font-semibold">Absent</span>
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="text-yellow-600 focus:ring-yellow-500 dark:focus:ring-yellow-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $currentStatus == 'late' ? 'checked' : '' }}>
                                                        <span class="ml-2 text-yellow-600 dark:text-yellow-400 font-semibold">Late</span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Save Attendance') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($batch_id && $students->count() == 0)
                <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                No students found in this batch.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
