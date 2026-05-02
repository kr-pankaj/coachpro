<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Lead') }}: {{ $enquiry->student_name }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 sm:p-8">
                    
                    <form method="POST" action="{{ route('enquiries.update', $enquiry) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Student Name & Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="student_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Student Name <span class="text-red-500">*</span></label>
                                <input type="text" name="student_name" id="student_name" value="{{ old('student_name', $enquiry->student_name) }}" required
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                                <x-input-error :messages="$errors->get('student_name')" class="mt-2" />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $enquiry->phone) }}" required
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Email & Course -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $enquiry->email) }}"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <label for="course_interested" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Course Interested In</label>
                                <input type="text" name="course_interested" id="course_interested" value="{{ old('course_interested', $enquiry->course_interested) }}"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                                <x-input-error :messages="$errors->get('course_interested')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Status & Follow Up -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select name="status" id="status" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" x-data x-on:change="document.getElementById('convert-option').style.display = $event.target.value === 'converted' ? 'block' : 'none'">
                                    <option value="new" {{ old('status', $enquiry->status) == 'new' ? 'selected' : '' }}>New Enquiry</option>
                                    <option value="contacted" {{ old('status', $enquiry->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="demo_scheduled" {{ old('status', $enquiry->status) == 'demo_scheduled' ? 'selected' : '' }}>Demo Scheduled</option>
                                    <option value="converted" {{ old('status', $enquiry->status) == 'converted' ? 'selected' : '' }}>Converted (Joined)</option>
                                    <option value="lost" {{ old('status', $enquiry->status) == 'lost' ? 'selected' : '' }}>Lost (Not interested)</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                
                                <div id="convert-option" class="mt-3 p-3 bg-indigo-50 rounded-lg" style="display: {{ old('status', $enquiry->status) == 'converted' ? 'block' : 'none' }};">
                                    <label class="flex items-start">
                                        <input type="checkbox" name="convert_to_student" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm mt-0.5" {{ old('convert_to_student') ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-indigo-800 font-medium">Auto-convert to Student<br><span class="text-xs text-indigo-600 font-normal">This will take you to the Add Student page with their details pre-filled.</span></span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="next_follow_up_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Next Follow-up Date</label>
                                <input type="date" name="next_follow_up_date" id="next_follow_up_date" value="{{ old('next_follow_up_date', $enquiry->next_follow_up_date ? $enquiry->next_follow_up_date->format('Y-m-d') : '') }}"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                                <x-input-error :messages="$errors->get('next_follow_up_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Any specific requirements or conversation details..."
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm resize-none">{{ old('notes', $enquiry->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('enquiries.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-sm transition-transform hover:scale-[1.02]" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                                Update Lead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
