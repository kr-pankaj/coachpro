<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Study Material Library') }}
                </h2>
                <p class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.2em] mt-1">Knowledge Hub & Resource Sharing</p>
            </div>

            @if(auth()->user()->role !== 'student')
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="px-6 py-2.5 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                Upload New Material
            </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($materials as $material)
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group transition-all hover:shadow-2xl hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center {{ 
                            $material->type === 'pdf' ? 'bg-rose-50 text-rose-600' : 
                            ($material->type === 'video' ? 'bg-blue-50 text-blue-600' : 
                            ($material->type === 'link' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'))
                        }}">
                            @if($material->type === 'pdf')
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/><path d="M9 15l2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            @elseif($material->type === 'video')
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            @elseif($material->type === 'link')
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            @else
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                            @endif
                        </div>
                        
                        @if(auth()->user()->role !== 'student')
                        <form action="{{ route('study_materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-rose-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="mb-6">
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">{{ $material->batch->name ?? 'All Batches' }}</p>
                        <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $material->title }}</h4>
                        @if($material->description)
                        <p class="text-xs text-gray-500 mt-2 font-medium line-clamp-2">{{ $material->description }}</p>
                        @endif
                    </div>

                    <a href="{{ $material->file_url }}" target="_blank" class="w-full py-4 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-gray-900 transition-all flex items-center justify-center gap-2">
                        View Resource
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                    </a>

                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mt-4 text-center">Uploaded {{ $material->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Library is Empty</h3>
                    <p class="text-sm text-gray-500 font-bold mt-2 uppercase tracking-widest">Start uploading knowledge resources</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Upload Modal --}}
    @if(auth()->user()->role !== 'student')
    <div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-950/80 backdrop-blur-sm" onclick="document.getElementById('uploadModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[3rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="p-12">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Share New Material</h3>
                        <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l18 18" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                            <ul class="list-disc list-inside text-[10px] font-bold text-rose-600 uppercase tracking-widest space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('study_materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Title</label>
                                <input type="text" name="title" required class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Material Type</label>
                                <select name="type" required class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white">
                                    <option value="pdf">PDF Document</option>
                                    <option value="video">Video Link</option>
                                    <option value="link">External Link</option>
                                    <option value="document">Other Document</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Assign to Batch</label>
                                <select name="batch_id" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white">
                                    <option value="">All Batches</option>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->name }} ({{ $batch->subject }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Resource Source</label>
                                <div class="flex items-center gap-4 mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="source" value="file" checked onclick="toggleSource('file')" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-[10px] font-black uppercase text-gray-600">Upload File</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="source" value="url" onclick="toggleSource('url')" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-[10px] font-black uppercase text-gray-600">External URL</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="fileInputSection">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">
                                Choose File 
                                <span class="text-indigo-500 ml-2">(Max 10MB | PDF, DOCX, ZIP supported)</span>
                            </label>
                            <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx" class="w-full bg-gray-50 dark:bg-gray-900 border-dashed border-2 border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-xs font-bold text-gray-500 cursor-pointer hover:bg-gray-100 transition-all">
                        </div>

                        <div id="urlInputSection" class="hidden">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">External URL (Drive/YouTube/Website)</label>
                            <input type="url" name="external_url" placeholder="https://..." class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Description (Optional)</label>
                            <textarea name="description" rows="3" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-xs font-bold focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"></textarea>
                        </div>

                        <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] text-[10px] font-black uppercase tracking-[0.2em] hover:bg-gray-950 transition-all shadow-2xl">
                            Publish to Library
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSource(type) {
            if (type === 'file') {
                document.getElementById('fileInputSection').classList.remove('hidden');
                document.getElementById('urlInputSection').classList.add('hidden');
            } else {
                document.getElementById('fileInputSection').classList.add('hidden');
                document.getElementById('urlInputSection').classList.remove('hidden');
            }
        }

        // Auto-open modal if there are validation errors
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('uploadModal').classList.remove('hidden');
            });
        @endif
    </script>
    @endif
</x-app-layout>
