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
                <div class="p-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 rounded-3xl text-emerald-800 dark:text-emerald-400 text-xs font-black uppercase tracking-widest">✅ {{ session('success') }}</div>
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
                        @else Great job! Your profile is 100% complete. 🎉
                        @endif
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('institute.settings.update') }}" class="space-y-6">
                @csrf @method('PUT')

                {{-- Basic Info --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">🏫 Basic Information</h3>
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
                    <h3 class="font-semibold text-gray-800 mb-1">📝 Institute Description</h3>
                    <p class="text-xs text-gray-400 mb-3">This appears on your student registration page. Describe your teaching approach, subjects offered, achievements, and what makes your institute unique. Example: <em>"We specialise in Science and Maths for classes 9–12, with a 95% board exam success rate since 2015."</em></p>
                    <textarea name="description" rows="4" placeholder="Tell students about your institute — subjects, teaching style, achievements, batch sizes..." class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400 resize-none">{{ old('description', $institute->description) }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-right text-xs text-gray-400 mt-1" id="desc-count">0/1000 chars</p>
                </div>

                {{-- Contact Info --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">📞 Contact Information</h3>
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
                    <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">📍 Address</h3>
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
                    <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">🎨 Branding</h3>
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
                    <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">⚙️ Configuration</h3>
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
