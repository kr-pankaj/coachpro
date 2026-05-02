<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Lead') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="mb-10">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">New Lead Entry</h3>
                        <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-widest">Prospect Details & Pipeline Routing</p>
                    </div>
                    
                    <form method="POST" action="{{ route('enquiries.store') }}" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Student Name</label>
                                <input type="text" name="student_name" id="student_name" value="{{ old('student_name') }}" required
                                    class="w-full !rounded-2xl !py-3 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="Enter full name">
                                <x-input-error :messages="$errors->get('student_name')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Mobile Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="w-full !rounded-2xl !py-3 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="+91 00000 00000">
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="w-full !rounded-2xl !py-3 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="prospect@email.com">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Subject/Course Interest</label>
                                <input type="text" name="course_interested" id="course_interested" value="{{ old('course_interested') }}" 
                                    class="w-full !rounded-2xl !py-3 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="e.g. NEET Biology, Grade 10">
                                <x-input-error :messages="$errors->get('course_interested')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Lead Status</label>
                                <select name="status" id="status" required class="w-full !rounded-2xl !py-3 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New Inquiry</option>
                                    <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="demo_scheduled" {{ old('status') == 'demo_scheduled' ? 'selected' : '' }}>Demo Scheduled</option>
                                    <option value="converted" {{ old('status') == 'converted' ? 'selected' : '' }}>Converted Student</option>
                                    <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost Lead</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Next Follow-up Date</label>
                                <input type="date" name="next_follow_up_date" id="next_follow_up_date" value="{{ old('next_follow_up_date', now()->addDay()->format('Y-m-d')) }}"
                                    class="w-full !rounded-2xl !py-3 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <x-input-error :messages="$errors->get('next_follow_up_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Internal Conversion Notes</label>
                            <textarea name="notes" id="notes" rows="4" placeholder="Brief summary of requirements or discussion..."
                                class="w-full !rounded-3xl !py-4 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm resize-none">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-6 pt-10 border-t border-gray-50 dark:border-gray-700/50">
                            <a href="{{ route('enquiries.index') }}" class="text-xs font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-colors">Discard</a>
                            <button type="submit" class="btn-gradient-indigo px-10 py-4">
                                Register Prospect
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
