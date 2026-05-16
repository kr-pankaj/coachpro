<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.badges.index') }}" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Create Badge') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Design a new reward milestone for students.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('superadmin.badges.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Badge Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Description</label>
                            <textarea name="description" rows="3" required
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">{{ old('description') }}</textarea>
                        </div>

                        {{-- Icon --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Icon (Emoji)</label>
                            <input type="text" name="icon" value="{{ old('icon') }}" required placeholder="e.g. 🏆"
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                        </div>

                        {{-- Color --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Theme Color</label>
                            <select name="color" required
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                                <option value="indigo">Indigo</option>
                                <option value="emerald">Emerald</option>
                                <option value="rose">Rose</option>
                                <option value="amber">Amber</option>
                                <option value="sky">Sky</option>
                                <option value="orange">Orange</option>
                                <option value="fuchsia">Fuchsia</option>
                            </select>
                        </div>

                        {{-- Requirement Type --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Requirement Type</label>
                            <select name="requirement_type" required
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                                <option value="xp_total">Total XP</option>
                                <option value="quizzes_count">Quizzes Completed</option>
                                <option value="streak_days">Longest Streak (Days)</option>
                                <option value="attendance_count">Attendance Count</option>
                            </select>
                        </div>

                        {{-- Requirement Value --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Threshold Value</label>
                            <input type="number" name="requirement_value" value="{{ old('requirement_value', 0) }}" required
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                        </div>

                        {{-- Is Secret --}}
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="hidden" name="is_secret" value="0">
                                    <input type="checkbox" name="is_secret" value="1" {{ old('is_secret') ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-all after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                                </div>
                                <span class="text-xs font-black text-gray-600 uppercase tracking-widest">Secret Badge (Hidden until earned)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-end">
                        <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">
                            Save Badge
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
