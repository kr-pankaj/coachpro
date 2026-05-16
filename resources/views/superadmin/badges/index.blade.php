<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Achievement Badges') }} <span class="text-indigo-600">🏅</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Configure automated rewards for student milestones.</p>
            </div>
            <a href="{{ route('superadmin.badges.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
                Create New Badge
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Badge</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Requirement</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($badges as $badge)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-{{ $badge->color }}-50 text-{{ $badge->color }}-600 flex items-center justify-center text-2xl shadow-sm border border-{{ $badge->color }}-100">
                                        {{ $badge->icon }}
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 dark:text-white">{{ $badge->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $badge->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ str_replace('_', ' ', $badge->requirement_type) }}: {{ $badge->requirement_value }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @if($badge->is_secret)
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Secret</span>
                                @else
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Public</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('superadmin.badges.edit', $badge) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </a>
                                    <form action="{{ route('superadmin.badges.destroy', $badge) }}" method="POST" onsubmit="return confirm('Delete this badge?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold italic text-sm">No badges configured yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-8 py-6">
                    {{ $badges->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
