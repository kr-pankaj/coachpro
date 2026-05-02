<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Fees') }}
            </h2>
            <a href="{{ route('fees.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Record Fee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Premium Filter Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('fees.index') }}" class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Student Search</label>
                        <x-text-input id="search" name="search" type="text" class="block w-full !rounded-2xl !py-3" placeholder="Search by student name..." :value="request('search')" />
                    </div>
                    <div class="w-full md:w-48 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Payment Status</label>
                        <select id="status" name="status" class="block w-full !rounded-2xl !py-3 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="w-full md:w-48 space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Month/Year</label>
                        <x-text-input id="month_year" name="month_year" type="month" class="block w-full !rounded-2xl !py-3" :value="request('month_year')" />
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit" class="btn-gradient-indigo flex-1 md:flex-none px-8">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'month_year']))
                            <a href="{{ route('fees.index') }}" class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-gray-100 transition-all border border-gray-100 dark:border-gray-600">
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
                            <th>Amount (₹)</th>
                            <th>Billing Month</th>
                            <th>Payment Status</th>
                            <th>Settlement Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fees as $fee)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-900/30 text-violet-600 font-black flex items-center justify-center">
                                            {{ substr($fee->student?->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $fee->student?->name ?? 'Unknown' }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $fee->student?->batch?->name ?? 'No Batch' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-black text-gray-900 dark:text-white">
                                        ₹{{ number_format($fee->amount, 0) }}
                                    </span>
                                </td>
                                <td class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                                    {{ $fee->month_year }}
                                </td>
                                <td>
                                    @if($fee->status == 'paid')
                                        <span class="badge-premium badge-emerald">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                            Paid
                                        </span>
                                    @else
                                        <span class="badge-premium badge-rose">
                                            <span class="w-1 h-1 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-xs font-bold text-gray-400 italic">
                                    {{ $fee->payment_date ? \Carbon\Carbon::parse($fee->payment_date)->format('M d, Y') : '-' }}
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        @if($fee->status == 'paid')
                                            <a href="{{ route('fees.receipt', $fee) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all" title="Download Receipt">
                                                <x-icons.download class="w-4 h-4" />
                                            </a>
                                        @endif
                                        <a href="{{ route('fees.edit', $fee) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all" title="Edit Record">
                                            <x-icons.edit class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('fees.destroy', $fee) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all" title="Delete Record">
                                                <x-icons.delete class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <p class="text-sm text-gray-400 font-bold italic">No financial records matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $fees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
