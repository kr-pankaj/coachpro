<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Certificate Designer') }}
            </h2>
            <a href="{{ route('certificates.preview') }}" target="_blank" class="no-loader inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                Preview Design
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-8 border-b border-gray-50 dark:border-gray-700">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Customize Your Certificate</h3>
                    <p class="text-sm text-gray-400 font-bold mt-1">Configure how your students' certificates will look.</p>
                </div>

                <form action="{{ route('certificates.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Left Column: Basics --}}
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <x-input-label for="title" :value="__('Certificate Title')" />
                                <x-text-input id="title" name="title" type="text" class="block w-full" :value="old('title', $template->title)" required />
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Example: Certificate of Achievement, Diploma, etc.</p>
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="body_text" :value="__('Main Certificate Text')" />
                                <textarea id="body_text" name="body_text" rows="5" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-quonix-purple dark:focus:border-quonix-purple focus:ring-quonix-purple dark:focus:ring-quonix-purple rounded-2xl shadow-sm" required>{{ old('body_text', $template->body_text ?? 'This is to certify that {student_name} has successfully completed the {course} course at {institute_name} on {date}.') }}</textarea>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-[10px] font-black text-gray-500 uppercase tracking-widest">{student_name}</span>
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-[10px] font-black text-gray-500 uppercase tracking-widest">{course}</span>
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-[10px] font-black text-gray-500 uppercase tracking-widest">{date}</span>
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-[10px] font-black text-gray-500 uppercase tracking-widest">{institute_name}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Assets --}}
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <x-input-label for="background_image" :value="__('Background Frame (Optional)')" />
                                <input type="file" name="background_image" id="background_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-quonix-purple file:text-white hover:file:bg-indigo-600">
                                @if($template->background_image)
                                    <p class="text-xs text-emerald-500 font-bold mt-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Custom Background Active
                                    </p>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <x-input-label for="authorized_signatory_name" :value="__('Signatory Name')" />
                                    <x-text-input id="authorized_signatory_name" name="authorized_signatory_name" type="text" class="block w-full" :value="old('authorized_signatory_name', $template->authorized_signatory_name)" placeholder="e.g. John Doe" />
                                </div>
                                <div class="space-y-2">
                                    <x-input-label for="authorized_signatory_designation" :value="__('Signatory Title')" />
                                    <x-text-input id="authorized_signatory_designation" name="authorized_signatory_designation" type="text" class="block w-full" :value="old('authorized_signatory_designation', $template->authorized_signatory_designation)" placeholder="e.g. Director" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="signature_image" :value="__('Digital Signature Image')" />
                                <input type="file" name="signature_image" id="signature_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-quonix-purple file:text-white hover:file:bg-indigo-600">
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-50 dark:border-gray-700 flex justify-end">
                        <button type="submit" class="btn-gradient-indigo px-12 py-4">
                            Save Design Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
