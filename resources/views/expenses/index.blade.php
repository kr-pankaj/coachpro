<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">Expense Manager</h2>
                <p class="text-sm text-gray-500 mt-1">Track all institute expenses and monitor batch profitability.</p>
            </div>
            <a href="{{ route('expenses.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white shadow-lg shadow-rose-200 hover:scale-105 active:scale-95 transition-all"
               style="background:linear-gradient(135deg,#f43f5e,#e11d48);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                Record Expense
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @php
                $summaryCards = [
                    ['label' => 'This Month', 'value' => '₹' . number_format($totalThisMonth), 'color' => 'rose',    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['label' => 'All Time',   'value' => '₹' . number_format($totalAllTime),   'color' => 'indigo',  'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['label' => 'Categories', 'value' => $breakdown->count() . ' active',       'color' => 'violet',  'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ];
                @endphp
                @foreach($summaryCards as $sc)
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-{{ $sc['color'] }}-50 dark:bg-{{ $sc['color'] }}-900/30 text-{{ $sc['color'] }}-600 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $sc['icon'] }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $sc['label'] }}</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $sc['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Category Breakdown --}}
            @if($breakdown->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-6">This Month by Category</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($categories as $key => $meta)
                    @php $amt = $breakdown[$key] ?? 0; @endphp
                    <div class="text-center p-4 rounded-2xl bg-{{ $meta['color'] }}-50 dark:bg-{{ $meta['color'] }}-900/20 {{ $amt > 0 ? 'opacity-100' : 'opacity-40' }}">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">{{ $meta['label'] }}</p>
                        <p class="text-lg font-black text-{{ $meta['color'] }}-600 mt-1">₹{{ number_format($amt) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Filters + Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                {{-- Filters --}}
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-wrap gap-3 items-end">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Month</label>
                            <input type="month" name="month" value="{{ $month }}"
                                   class="text-sm border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Category</label>
                            <select name="category" class="text-sm border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-rose-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $meta)
                                    <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Batch</label>
                            <select name="batch_id" class="text-sm border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-rose-500">
                                <option value="">All (incl. overhead)</option>
                                <option value="none" {{ $batchId === 'none' ? 'selected' : '' }}>Overhead only (no batch)</option>
                                @foreach($batches as $b)
                                    <option value="{{ $b->id }}" {{ $batchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-5 py-2 bg-rose-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('expenses.index') }}" class="px-5 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <th class="text-left text-[9px] font-black text-gray-400 uppercase tracking-widest px-6 py-4">Date</th>
                                <th class="text-left text-[9px] font-black text-gray-400 uppercase tracking-widest px-6 py-4">Title</th>
                                <th class="text-left text-[9px] font-black text-gray-400 uppercase tracking-widest px-6 py-4">Category</th>
                                <th class="text-left text-[9px] font-black text-gray-400 uppercase tracking-widest px-6 py-4">Batch / Scope</th>
                                <th class="text-right text-[9px] font-black text-gray-400 uppercase tracking-widest px-6 py-4">Amount</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @forelse($expenses as $exp)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-bold whitespace-nowrap">
                                    {{ $exp->expense_date->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $exp->title }}</p>
                                    @if($exp->notes)
                                        <p class="text-[10px] text-gray-400 mt-0.5 truncate max-w-xs">{{ $exp->notes }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                        bg-{{ $exp->category_color }}-50 dark:bg-{{ $exp->category_color }}-900/30
                                        text-{{ $exp->category_color }}-600">
                                        {{ $exp->category_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($exp->batch)
                                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">{{ $exp->batch->name }}</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Institute Overhead</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-rose-600">₹{{ number_format($exp->amount) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('expenses.edit', $exp) }}"
                                           class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('expenses.destroy', $exp) }}" onsubmit="return confirm('Delete this expense?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 bg-rose-50 dark:bg-rose-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-rose-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No expenses recorded</p>
                                    <p class="text-xs text-gray-400 mt-2">Start by recording your first expense.</p>
                                    <a href="{{ route('expenses.create') }}" class="inline-flex mt-4 px-5 py-2 bg-rose-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-colors">
                                        Record Expense
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $expenses->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
