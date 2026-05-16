<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-900 dark:text-white uppercase tracking-tighter">
                {{ __('Write New Article') }}
            </h2>
            <a href="{{ route('superadmin.kb-articles.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('superadmin.kb-articles.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 p-10 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="md:col-span-2">
                            <x-input-label for="title" :value="__('Article Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full text-lg font-bold" placeholder="e.g. How to manage student attendance" required autofocus />
                        </div>

                        <div>
                            <x-input-label for="kb_category_id" :value="__('Category')" />
                            <select name="kb_category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-2xl shadow-sm" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-8 pt-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_published" class="sr-only peer" checked>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                <span class="ms-3 text-sm font-black text-gray-900 dark:text-gray-300 uppercase tracking-widest">Publish</span>
                            </label>

                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_internal_only" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-rose-600"></div>
                                <span class="ms-3 text-sm font-black text-gray-900 dark:text-gray-300 uppercase tracking-widest">Internal Only</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="content" :value="__('Article Content (HTML/Markdown Supported)')" />
                        <textarea id="content" name="content" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-2xl shadow-sm min-h-[500px] font-mono p-6 text-sm" placeholder="Write your guide here..." required></textarea>
                        <p class="mt-4 text-xs text-gray-400 font-bold uppercase tracking-widest">Tip: Use standard HTML tags for formatting (e.g. <h3>, <p>, <ul>, <li>).</p>
                    </div>

                    <div class="mt-12 flex justify-end">
                        <x-primary-button class="px-10 py-4 rounded-2xl text-sm">{{ __('Publish Article') }}</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
