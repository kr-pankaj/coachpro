<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tight">
                    {{ __('Professional Portfolio') }} <span class="text-indigo-600">💼</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Stand out to employers by showcasing your best skills and achievements.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('student.portfolio.update') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-10">
                    <div class="space-y-8">
                        {{-- Professional Bio --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Professional Bio</label>
                            <textarea name="bio" rows="4" placeholder="e.g. Aspiring Full-Stack Developer with a passion for building scalable web applications..."
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">{{ old('bio', $student->bio) }}</textarea>
                        </div>

                        {{-- Technical Skills --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Technical Skills (Comma separated)</label>
                            <input type="text" name="skills" value="{{ old('skills', is_array($student->skills) ? implode(', ', $student->skills) : '') }}" placeholder="e.g. React, Python, AWS, Docker, SQL"
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                            <p class="text-[9px] text-gray-400 mt-2 ml-2">Add your strongest technologies and tools.</p>
                        </div>

                        {{-- Notable Achievements --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Notable Achievements (Comma separated)</label>
                            <textarea name="notable_achievements" rows="3" placeholder="e.g. Winner of Smart India Hackathon 2025, Top 1% in GATE, Published 2 Research Papers"
                                class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">{{ old('notable_achievements', is_array($student->notable_achievements) ? implode(', ', $student->notable_achievements) : '') }}</textarea>
                        </div>

                        {{-- Featured Projects --}}
                        <div x-data="{ 
                            projects: {{ json_encode($student->projects ?? []) }},
                            addProject() {
                                if (this.projects.length < 3) {
                                    this.projects.push({ title: '', tech: '', link: '', description: '' });
                                }
                            },
                            removeProject(index) {
                                this.projects.splice(index, 1);
                            }
                        }" class="pt-8 border-t border-gray-50 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-6">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Featured Projects (Max 3)</label>
                                <button type="button" @click="addProject()" x-show="projects.length < 3"
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">
                                    + Add Project
                                </button>
                            </div>

                            <div class="space-y-6">
                                <template x-for="(project, index) in projects" :key="index">
                                    <div class="p-6 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-100 dark:border-gray-700 relative group">
                                        <button type="button" @click="removeProject(index)" 
                                            class="absolute top-4 right-4 text-gray-400 hover:text-rose-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                                        </button>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="md:col-span-2">
                                                <input type="text" :name="'projects['+index+'][title]'" x-model="project.title" placeholder="Project Title"
                                                    class="w-full bg-white dark:bg-gray-800 border-transparent rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all">
                                            </div>
                                            <div>
                                                <input type="text" :name="'projects['+index+'][tech]'" x-model="project.tech" placeholder="Tech Stack (e.g. React, Laravel)"
                                                    class="w-full bg-white dark:bg-gray-800 border-transparent rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all">
                                            </div>
                                            <div>
                                                <input type="url" :name="'projects['+index+'][link]'" x-model="project.link" placeholder="GitHub/Live Link"
                                                    class="w-full bg-white dark:bg-gray-800 border-transparent rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all">
                                            </div>
                                            <div class="md:col-span-2">
                                                <textarea :name="'projects['+index+'][description]'" x-model="project.description" rows="2" placeholder="Brief description of what you built..."
                                                    class="w-full bg-white dark:bg-gray-800 border-transparent rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-600 transition-all"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Social Links --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">GitHub Profile URL</label>
                                <input type="url" name="github_url" value="{{ old('github_url', $student->github_url) }}" placeholder="https://github.com/yourusername"
                                    class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">LinkedIn Profile URL</label>
                                <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $student->linkedin_url) }}" placeholder="https://linkedin.com/in/yourusername"
                                    class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div class="pt-6 border-t border-gray-50 dark:border-gray-700">
                            <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl cursor-pointer group hover:bg-gray-100 transition-all">
                                <span class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest">Show Attendance Stats on Public Portfolio</span>
                                <div class="relative inline-flex items-center">
                                    <input type="hidden" name="show_attendance_on_portfolio" value="0">
                                    <input type="checkbox" name="show_attendance_on_portfolio" id="show_attendance_on_portfolio" value="1" 
                                        {{ old('show_attendance_on_portfolio', $student->show_attendance_on_portfolio) ? 'checked' : '' }} 
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </div>
                            </label>
                            <p class="text-[9px] text-gray-400 mt-2 ml-4">Turn this on if you want to showcase your consistency to employers.</p>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-between items-center">
                        <a href="{{ route('student.portfolio', ['slug' => $resolved_institute->slug, 'student' => $student->id]) }}" target="_blank" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">
                            Preview Public Portfolio
                        </a>
                        <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">
                            Save Career Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
