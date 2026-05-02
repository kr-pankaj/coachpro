<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Institute Settings</h2>
    </x-slot>

    <div class="py-12 bg-[#f8fafc] dark:bg-gray-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Branded Header --}}
            <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Institute Hub</h2>
                        <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.2em] mt-1">Configure Your Digital Campus</p>
                        
                        <div class="mt-6 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800/50 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-indigo-600 flex items-center justify-center text-indigo-600 dark:text-white shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Branded Registration URL</p>
                                <a href="http://{{ $institute->slug }}.{{ trim(str_replace(['http://', 'https://', '/'], '', config('app.url')), '/') }}/student/register" target="_blank" class="text-sm font-bold text-gray-900 dark:text-white hover:text-indigo-600 transition-colors">
                                    {{ $institute->slug }}.{{ trim(str_replace(['http://', 'https://', '/'], '', config('app.url')), '/') }}/student/register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="p-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 rounded-3xl text-emerald-800 dark:text-emerald-400 text-xs font-black uppercase tracking-widest flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Profile Completion --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
                <div class="relative w-14 h-14 shrink-0">
                    <svg class="rotate-[-90deg]" viewBox="0 0 36 36" width="56" height="56">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#4f46e5" stroke-width="3"
                            stroke-dasharray="{{ $profilePct }} {{ 100 - $profilePct }}" stroke-linecap="round"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-indigo-700">{{ $profilePct }}%</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Profile Completion</p>
                    <p class="text-sm text-gray-500">
                        @if($profilePct < 50) Fill in more details to make your profile stand out to students.
                        @elseif($profilePct < 100) Almost there! A complete profile builds trust with students.
                        @else Great job! Your profile is 100% complete.
                        @endif
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('institute.settings.update') }}" class="space-y-6">
                @csrf @method('PUT')

                {{-- Basic Info --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="font-semibold text-gray-800">Basic Information</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Institute Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $institute->name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Slug (Subdomain) <span class="text-red-500">*</span></label>
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100">
                                <input type="text" name="slug" value="{{ old('slug', $institute->slug) }}" required class="flex-1 px-3 py-2.5 text-sm focus:outline-none">
                                <span class="px-3 py-2.5 bg-gray-50 text-xs text-gray-400 border-l border-gray-200 whitespace-nowrap">.{{ trim(str_replace(['http://', 'https://'], '', config('app.url')), '/') }}</span>
                            </div>
                            @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Established Year</label>
                            <input type="number" name="established_year" value="{{ old('established_year', $institute->established_year) }}" min="1900" max="{{ date('Y') }}" placeholder="e.g. 2018" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Brand Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="brand_color" value="{{ old('brand_color', $institute->brand_color ?? '#4f46e5') }}" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                                <span class="text-xs text-gray-400">Shown on your student registration page</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="font-semibold text-gray-800">Institute Description</h3>
                    </div>
                    <p class="text-xs text-gray-400 mb-3">This appears on your student registration page. Describe your teaching approach, subjects offered, achievements, and what makes your institute unique. Example: <em>"We specialise in Science and Maths for classes 9–12, with a 95% board exam success rate since 2015."</em></p>
                    <textarea name="description" rows="4" placeholder="Tell students about your institute — subjects, teaching style, achievements, batch sizes..." class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400 resize-none">{{ old('description', $institute->description) }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-right text-xs text-gray-400 mt-1" id="desc-count">0/1000 chars</p>
                </div>

                {{-- Contact Info --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="font-semibold text-gray-800">Contact Information</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $institute->phone) }}" placeholder="+91 98765 43210" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $institute->contact_email) }}" placeholder="info@yourinstitute.com" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <input type="url" name="website" value="{{ old('website', $institute->website) }}" placeholder="https://yourinstitute.com" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="font-semibold text-gray-800">Address</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                            <textarea name="address" rows="2" placeholder="Building name, street, area..." class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400 resize-none">{{ old('address', $institute->address) }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="city" value="{{ old('city', $institute->city) }}" placeholder="Mumbai" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <select name="state" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                                    <option value="">Select state</option>
                                    @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Puducherry'] as $state)
                                    <option value="{{ $state }}" {{ old('state', $institute->state) === $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pincode</label>
                                <input type="text" name="pincode" value="{{ old('pincode', $institute->pincode) }}" placeholder="400001" maxlength="6" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Branding --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a1 1 0 01.894.553L10.236 4H14a2 2 0 012 2v3m-2 11a2 2 0 01-2-2v-5a2 2 0 012-2h3a2 2 0 012 2v5a2 2 0 01-2 2h-3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="font-semibold text-gray-800">Branding</h3>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo URL</label>
                        <input type="url" name="logo_url" value="{{ old('logo_url', $institute->logo_url) }}" placeholder="https://yourinstitute.com/logo.png" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                        <p class="text-xs text-gray-400 mt-1">Paste a direct link to your institute logo image. Appears on student registration page. Recommended: 200×200px PNG with transparent background.</p>
                        @if($institute->logo_url)
                            <img src="{{ $institute->logo_url }}" alt="Logo" class="mt-3 h-16 w-16 object-contain rounded-lg border border-gray-100">
                        @endif
                    </div>
                </div>

                {{-- Onboarding Config --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        <h3 class="font-semibold text-gray-800">Configuration</h3>
                    </div>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" name="allow_student_self_registration" class="mt-0.5 w-4 h-4 accent-indigo-600" {{ $institute->allow_student_self_registration ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm font-medium text-gray-800 group-hover:text-indigo-600">Allow student self-registration</p>
                            <p class="text-xs text-gray-400 mt-0.5">Students can register themselves using your unique link:
                                <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">{{ $institute->slug }}.{{ trim(str_replace(['http://', 'https://', '/'], '', config('app.url')), '/') }}/student/register</code>
                            </p>
                        </div>
                    </label>
                    @if($institute->allow_student_self_registration)
                    <div class="mt-4 p-3 bg-indigo-50 rounded-lg flex items-center gap-3">
                        <code class="text-xs text-indigo-800 flex-1 break-all">http://{{ $institute->slug }}.{{ trim(str_replace(['http://', 'https://', '/'], '', config('app.url')), '/') }}/student/register</code>
                        @php $regUrl = "http://" . $institute->slug . "." . trim(str_replace(['http://', 'https://', '/'], '', config('app.url')), '/') . "/student/register"; @endphp
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $regUrl }}');this.textContent='Copied!';" class="text-xs font-semibold text-indigo-600 shrink-0">Copy</button>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end pb-20 sm:pb-0">
                    <button type="submit" class="px-6 py-3 text-sm font-semibold text-white rounded-xl" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        Save All Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const ta = document.querySelector('textarea[name=description]');
        const counter = document.getElementById('desc-count');
        if(ta && counter) {
            const update = () => counter.textContent = ta.value.length + '/1000 chars';
            update(); ta.addEventListener('input', update);
        }
    </script>
</x-app-layout>
