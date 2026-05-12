@extends('layouts.superadmin-app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 lg:p-10">

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Contact Leads</h1>
            <p class="text-sm text-gray-500 mt-1">Inbound enquiries from the QuonixAI landing page</p>
        </div>

        {{-- Filter tabs --}}
        <div class="flex gap-2 flex-wrap text-xs font-bold uppercase tracking-wide">
            @foreach(['all' => 'All', 'new' => 'New 🔔', 'contacted' => 'Contacted', 'converted' => 'Converted', 'closed' => 'Closed'] as $val => $label)
                <a href="{{ route('superadmin.contact-leads.index', $val !== 'all' ? ['status' => $val] : []) }}"
                   class="px-4 py-2 rounded-full border transition-all
                   {{ request('status', 'all') === $val
                        ? 'bg-pink-500 text-white border-pink-500'
                        : 'bg-white text-gray-500 border-gray-200 hover:border-pink-400' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-medium text-sm">{{ session('success') }}</div>
    @endif

    {{-- Leads Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs font-black uppercase tracking-wider text-gray-400 border-b border-gray-100">
                        <th class="px-6 py-4 text-left">Lead</th>
                        <th class="px-6 py-4 text-left">Institute</th>
                        <th class="px-6 py-4 text-left">Plan</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Received</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50 transition-colors" x-data="{ open: false }">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ $lead->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $lead->email }}</p>
                                @if($lead->phone)
                                    <p class="text-gray-400 text-xs">{{ $lead->phone }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-700">{{ $lead->institute_name ?? '—' }}</p>
                                <p class="text-gray-400 text-xs">{{ $lead->city ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $planColors = ['monthly' => 'blue', 'six_month' => 'violet', 'custom' => 'amber'];
                                    $planColor = $planColors[$lead->plan_interest] ?? 'gray';
                                @endphp
                                @if($lead->plan_interest)
                                    <span class="px-3 py-1 rounded-full text-xs font-black bg-{{ $planColor }}-50 text-{{ $planColor }}-700 border border-{{ $planColor }}-200">
                                        {{ ucfirst(str_replace('_', ' ', $lead->plan_interest)) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = ['new' => 'rose', 'contacted' => 'amber', 'converted' => 'emerald', 'closed' => 'gray'];
                                    $sc = $statusColors[$lead->status] ?? 'gray';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-black bg-{{ $sc }}-50 text-{{ $sc }}-700 border border-{{ $sc }}-200">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs whitespace-nowrap">
                                {{ $lead->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <button @click="open = !open" class="text-xs font-black text-indigo-600 hover:text-indigo-800 transition-colors">
                                    Edit / Notes
                                </button>
                            </td>
                        </tr>
                        {{-- Expandable Edit Row --}}
                        <tr x-show="open" x-cloak class="bg-pink-50">
                            <td colspan="6" class="px-6 py-4">
                                @if($lead->message)
                                    <p class="text-sm text-gray-600 mb-4 italic">"{{ $lead->message }}"</p>
                                @endif
                                <form action="{{ route('superadmin.contact-leads.update', $lead) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="text-xs font-black uppercase tracking-wider text-gray-500 block mb-1">Status</label>
                                        <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:border-pink-400">
                                            @foreach(['new','contacted','converted','closed'] as $s)
                                                <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1 min-w-[200px]">
                                        <label class="text-xs font-black uppercase tracking-wider text-gray-500 block mb-1">Admin Notes</label>
                                        <input type="text" name="admin_notes" value="{{ $lead->admin_notes }}" placeholder="Add notes..." class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-pink-400">
                                    </div>
                                    <button type="submit" class="px-5 py-2 rounded-xl bg-pink-500 text-white text-xs font-black hover:bg-pink-600 transition-all">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400 text-sm">No contact leads yet. They'll appear here once someone submits the form.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $leads->links() }}</div>
        @endif
    </div>
</div>
@endsection
