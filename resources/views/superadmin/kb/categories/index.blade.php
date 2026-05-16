<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-900 dark:text-white uppercase tracking-tighter">
                {{ __('Knowledge Base Categories') }}
            </h2>
            <button onclick="document.getElementById('createCategoryModal').classList.remove('hidden')" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-105 active:scale-95 transition-all">
                Add Category
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 p-8 shadow-sm hover:shadow-xl transition-all group">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center text-indigo-600">
                                @if($category->icon)
                                    <span class="text-2xl">{!! $category->icon !!}</span>
                                @else
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->icon }}', '{{ $category->description }}', {{ $category->order }})" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </button>
                                <form action="{{ route('superadmin.kb-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? All articles within it will be removed.')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-400 hover:text-rose-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500 mb-6 line-clamp-2">{{ $category->description ?? 'No description provided.' }}</p>
                        <div class="flex items-center justify-between pt-6 border-t border-gray-50 dark:border-gray-700">
                            <span class="text-xs font-black text-indigo-600 uppercase tracking-widest">{{ $category->articles_count }} Articles</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Order: {{ $category->order }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createCategoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Add Category</h3>
                    <button onclick="document.getElementById('createCategoryModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </button>
                </div>
                <form action="{{ route('superadmin.kb-categories.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Category Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="icon" :value="__('Icon (Emoji or SVG)')" />
                            <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full" placeholder="e.g. 📚" />
                        </div>
                        <div>
                            <x-input-label for="order" :value="__('Display Order')" />
                            <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" value="0" />
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-2xl shadow-sm" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="mt-10">
                        <x-primary-button class="w-full justify-center py-4 rounded-2xl">{{ __('Create Category') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editCategoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl w-full max-w-lg p-10 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Edit Category</h3>
                    <button onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="edit_name" :value="__('Category Name')" />
                            <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="edit_icon" :value="__('Icon (Emoji or SVG)')" />
                            <x-text-input id="edit_icon" name="icon" type="text" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="edit_order" :value="__('Display Order')" />
                            <x-text-input id="edit_order" name="order" type="number" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="edit_description" :value="__('Description')" />
                            <textarea id="edit_description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-2xl shadow-sm" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="mt-10">
                        <x-primary-button class="w-full justify-center py-4 rounded-2xl">{{ __('Save Changes') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function editCategory(id, name, icon, description, order) {
            document.getElementById('editForm').action = `/superadmin/kb-categories/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_icon').value = icon;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_order').value = order;
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>
