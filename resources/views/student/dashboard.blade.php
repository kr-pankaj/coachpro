<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ $student->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        You are enrolled in <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $student->batch ? $student->batch->name : 'No Batch Assigned Yet' }}</span> 
                        @if($student->batch && $student->batch->time_slot)
                            ({{ $student->batch->time_slot }})
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fees Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-lg font-bold mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">Fee Details</h4>
                        @if($fees->isEmpty())
                            <p class="text-sm text-gray-500">No fee records found.</p>
                        @else
                            <ul class="space-y-3">
                                @foreach($fees as $fee)
                                    <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <div>
                                            <p class="font-semibold">{{ $fee->month_year }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Paid on: {{ $fee->payment_date ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold mb-1 text-sm">₹{{ number_format($fee->paid_amount, 0) }}</p>
                                            @if($fee->due_amount > 0)
                                                <p class="text-[9px] text-rose-500 font-bold uppercase mb-1">Due: ₹{{ number_format($fee->due_amount, 0) }}</p>
                                            @endif

                                            <div class="flex items-center justify-end gap-2">
                                                @if($fee->status == 'paid')
                                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-tighter">Paid</span>
                                                @elseif($fee->status == 'partial')
                                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-indigo-100 text-indigo-800 uppercase tracking-tighter">Partial</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-800 uppercase tracking-tighter">Unpaid</span>
                                                @endif

                                                @if($fee->status !== 'pending')
                                                    <a href="{{ route('fees.receipt', $fee) }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase">Receipt</a>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Attendance Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-lg font-bold mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">Recent Attendance</h4>
                        @if($attendances->isEmpty())
                            <p class="text-sm text-gray-500">No attendance records found.</p>
                        @else
                            <ul class="space-y-3">
                                @foreach($attendances as $attendance)
                                    <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <p class="font-semibold">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</p>
                                        <div>
                                            @if($attendance->status == 'present')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Present</span>
                                            @elseif($attendance->status == 'late')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Late</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Absent</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Request Profile Update -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="text-lg font-bold mb-1 border-b pb-2 border-gray-200 dark:border-gray-700">Update Profile Info</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Changes will be sent to your institute for approval before taking effect.</p>

                    @if (session('success'))
                        <div class="mb-4 p-3 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/30 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-3 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/30 dark:text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile_requests.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $student->phone)" placeholder="Enter new phone number" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="parent_phone" :value="__('Parent\'s Phone')" />
                                <x-text-input id="parent_phone" class="block mt-1 w-full" type="text" name="parent_phone" :value="old('parent_phone', $student->parent_phone)" placeholder="Enter parent's phone" />
                                <x-input-error :messages="$errors->get('parent_phone')" class="mt-2" />
                            </div>
                            <div class="sm:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" placeholder="Enter new address">{{ old('address', $student->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-primary-button>{{ __('Submit Update Request') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
