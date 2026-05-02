<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record Fee Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('fees.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="student_id" :value="__('Student')" />
                                <select id="student_id" name="student_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required autofocus>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->batch ? $student->batch->name : 'No Batch' }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="amount" :value="__('Amount (₹)')" />
                                <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount')" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="month_year" :value="__('Fee For (Month/Year)')" />
                                <x-text-input id="month_year" class="block mt-1 w-full" type="month" name="month_year" :value="old('month_year', now()->format('Y-m'))" required />
                                <x-input-error :messages="$errors->get('month_year')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ old('status', 'paid') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="payment_date" :value="__('Payment Date')" />
                                <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date', now()->toDateString())" />
                                <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Save Fee Record') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
