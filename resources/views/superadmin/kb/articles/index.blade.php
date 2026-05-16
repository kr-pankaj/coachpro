<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-900 dark:text-white uppercase tracking-tighter">
                {{ __('Knowledge Base Articles') }}
            </h2>
            <a href="{{ route('superadmin.kb-articles.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-105 active:scale-95 transition-all">
                Write Article
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 p-8 shadow-sm">
                <form action="{{ route('superadmin.kb-articles.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="search" :value="__('Search Articles')" />
                        <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" placeholder="Title..." value="{{ request('search') }}" />
                    </div>
                    <div>
                        <x-input-label for="category_id" :value="__('Filter by Category')" />
                        <select name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-2xl shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <x-primary-button class="w-full justify-center py-3 rounded-2xl">{{ __('Filter') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-700">Article</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-700">Category</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-700">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($articles as $article)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $article->title }}</div>
                                    <div class="flex items-center gap-3 mt-1 text-[10px] text-gray-400 uppercase tracking-tighter">
                                        <span>{{ $article->views_count }} views</span>
                                        @if($article->feedback_count > 0)
                                            <span class="text-emerald-500 font-black">
                                                {{ round(($article->helpful_count / $article->feedback_count) * 100) }}% Helpful
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-[10px] font-black uppercase tracking-widest">
                                        {{ $article->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1">
                                        @if($article->is_published)
                                            <span class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-black uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Published
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1.5 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Draft
                                            </span>
                                        @endif
                                        
                                        @if($article->is_internal_only)
                                            <span class="flex items-center gap-1.5 text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                                                🔒 Internal Only
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('kb.show', $article->slug) }}" target="_blank" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="View Publicly">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </a>
                                        <a href="{{ route('superadmin.kb-articles.edit', $article) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </a>
                                        <form action="{{ route('superadmin.kb-articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Delete this article?')">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-gray-400 hover:text-rose-600 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5a2 2 0 00-2 2v12a2 2 0 002 2h5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </div>
                                        <p class="text-gray-500 font-bold">No articles found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
