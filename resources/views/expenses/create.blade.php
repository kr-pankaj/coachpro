<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('expenses.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">Record New Expense</h2>
                <p class="text-sm text-gray-500 mt-1">Log a salary, rent, utility bill, or any other cost.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-10">

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2 font-bold">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">Expense Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               placeholder="e.g. Teacher Salary - May, Electricity Bill"
                               class="w-full border border-gray-200 dark:border-gray-600 rounded-2xl px-5 py-3.5 text-sm font-medium text-gray-900 dark:text-white dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all placeholder-gray-400">
                    </div>

                    {{-- Category + Amount --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">Category *</label>
                            <select name="category" required
                                    class="w-full border border-gray-200 dark:border-gray-600 rounded-2xl px-5 py-3.5 text-sm font-medium text-gray-900 dark:text-white dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all">
                                @foreach($categories as $key => $meta)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">Amount (₹) *</label>
                            <input type="number" name="amount" value="{{ old('amount') }}" required min="0.01" step="0.01"
                                   placeholder="0.00"
                                   class="w-full border border-gray-200 dark:border-gray-600 rounded-2xl px-5 py-3.5 text-sm font-medium text-gray-900 dark:text-white dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    {{-- Date + Batch --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">Expense Date *</label>
                            <input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" required
                                   class="w-full border border-gray-200 dark:border-gray-600 rounded-2xl px-5 py-3.5 text-sm font-medium text-gray-900 dark:text-white dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                                Linked Batch
                                <span class="text-gray-300 font-normal normal-case">(optional)</span>
                            </label>
                            <select name="batch_id"
                                    class="w-full border border-gray-200 dark:border-gray-600 rounded-2xl px-5 py-3.5 text-sm font-medium text-gray-900 dark:text-white dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all">
                                <option value="">Institute Overhead (no batch)</option>
                                @foreach($batches as $b)
                                    <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">Notes <span class="text-gray-300 font-normal normal-case">(optional)</span></label>
                        <textarea name="notes" rows="3" placeholder="Any additional details..."
                                  class="w-full border border-gray-200 dark:border-gray-600 rounded-2xl px-5 py-3.5 text-sm font-medium text-gray-900 dark:text-white dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all resize-none placeholder-gray-400">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Tip --}}
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                        <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest mb-1">💡 Tip</p>
                        <p class="text-xs text-amber-700 dark:text-amber-400">Link a salary or material cost to a <strong>specific batch</strong> to see real batch profitability on the dashboard. Leave batch empty for institute-wide overhead like rent or electricity.</p>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <a href="{{ route('expenses.index') }}" class="flex-1 text-center px-6 py-3.5 rounded-2xl text-sm font-black text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 transition-colors uppercase tracking-widest">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 px-6 py-3.5 rounded-2xl text-sm font-black text-white shadow-lg shadow-rose-200 hover:scale-105 active:scale-95 transition-all uppercase tracking-widest"
                                style="background:linear-gradient(135deg,#f43f5e,#e11d48);">
                            Save Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
