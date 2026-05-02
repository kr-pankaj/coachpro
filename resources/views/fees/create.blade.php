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
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-2">
                                <x-input-label for="student_id" :value="__('Select Student')" />
                                <select id="student_id" name="student_id" class="block mt-1 w-full" required autofocus>
                                    <option value="">Search by name or batch...</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->batch ? $student->batch->name : 'No Batch' }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="total_amount" :value="__('Total Course Fee (₹)')" />
                                <x-text-input id="total_amount" class="block mt-1 w-full" type="number" step="0.01" name="total_amount" :value="old('total_amount')" required placeholder="e.g. 15000" />
                                <x-input-error :messages="$errors->get('total_amount')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="amount_paid" :value="__('Amount Paid Now (₹)')" />
                                <x-text-input id="amount_paid" class="block mt-1 w-full border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500" type="number" step="0.01" name="amount_paid" :value="old('amount_paid', 0)" placeholder="e.g. 5000" />
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Leave 0 if payment is pending</p>
                                <x-input-error :messages="$errors->get('amount_paid')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="discount_amount" :value="__('Discount Given (₹)')" />
                                <x-text-input id="discount_amount" class="block mt-1 w-full" type="number" step="0.01" name="discount_amount" :value="old('discount_amount', 0)" />
                                <x-input-error :messages="$errors->get('discount_amount')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="month_year" :value="__('Fee For (Month/Year)')" />
                                <x-text-input id="month_year" class="block mt-1 w-full" type="month" name="month_year" :value="old('month_year', now()->format('Y-m'))" required />
                                <x-input-error :messages="$errors->get('month_year')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="payment_date" :value="__('Transaction Date')" />
                                <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date', now()->toDateString())" />
                                <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="payment_method" :value="__('Payment Method')" />
                                <select id="payment_method" name="payment_method" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="Cash">Cash</option>
                                    <option value="GPay">Google Pay</option>
                                    <option value="PhonePe">PhonePe</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="remarks" :value="__('Internal Remarks')" />
                                <textarea id="remarks" name="remarks" rows="2" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Any notes about this payment...">{{ old('remarks') }}</textarea>
                                <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <x-primary-button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Generate Fee Record') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#student_id', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Search for a student...",
                dropdownParent: 'body'
            });
        });
    </script>
</x-app-layout>
