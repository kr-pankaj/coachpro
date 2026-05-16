<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Help Center | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif !important; }
        .mesh-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background-color: #ffffff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.05) 0, transparent 50%), 
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.05) 0, transparent 50%);
        }
    </style>
</head>
<body class="antialiased text-gray-900 bg-gray-50/50">
    <div class="mesh-bg"></div>

    {{-- Navigation --}}
    <nav class="bg-white/70 backdrop-blur-xl border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <x-application-logo class="h-8 w-auto" />
                <span class="font-black text-xl tracking-tighter uppercase">Help Center</span>
            </a>
            <a href="/" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 uppercase tracking-widest">Back to Website →</a>
        </div>
    </nav>

    {{-- Hero Search --}}
    <div class="py-20 px-6 bg-gradient-to-b from-white to-transparent">
        <div class="max-w-4xl mx-auto text-center" x-data="{ 
            query: '', 
            results: [], 
            loading: false,
            search() {
                if(this.query.length < 2) { this.results = []; return; }
                this.loading = true;
                fetch(`/help/search?q=${encodeURIComponent(this.query)}`)
                    .then(res => res.json())
                    .then(data => {
                        this.results = data;
                        this.loading = false;
                    });
            }
        }">
            <h1 class="text-5xl md:text-7xl font-black tracking-tighter text-gray-900 mb-6 uppercase">How can we help?</h1>
            <p class="text-xl text-gray-500 mb-12">Search our knowledge base or browse categories below.</p>
            
            <div class="relative max-w-2xl mx-auto">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <svg class="h-6 h-6 text-gray-400 group-focus-within:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="query" 
                        @input.debounce.300ms="search()"
                        class="block w-full pl-16 pr-6 py-6 bg-white border-2 border-gray-100 focus:border-indigo-500 focus:ring-0 rounded-[2rem] text-xl font-medium shadow-2xl shadow-indigo-500/10 transition-all placeholder:text-gray-300" 
                        placeholder="Type your question here..."
                    >
                    <div x-show="loading" class="absolute right-6 top-6">
                        <svg class="animate-spin h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                {{-- Search Results Dropdown --}}
                <div 
                    x-show="results.length > 0 || (query.length > 2 && !loading && results.length === 0)" 
                    x-transition
                    class="absolute z-50 mt-4 w-full bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden text-left"
                >
                    <template x-if="results.length > 0">
                        <div class="divide-y divide-gray-50">
                            <template x-for="result in results" :key="result.url">
                                <a :href="result.url" class="flex flex-col px-8 py-6 hover:bg-gray-50 transition-colors group">
                                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1" x-text="result.category"></span>
                                    <span class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors" x-text="result.title"></span>
                                </a>
                            </template>
                        </div>
                    </template>
                    <template x-if="query.length > 2 && !loading && results.length === 0">
                        <div class="px-8 py-10 text-center">
                            <p class="text-gray-500 font-bold">No results found for "<span x-text="query"></span>"</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories Grid --}}
    <div class="max-w-7xl mx-auto px-6 py-20">
        <h2 class="text-sm font-black text-indigo-600 uppercase tracking-[0.3em] mb-12 text-center">Browse by Category</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                <a href="{{ route('kb.category', $category->slug) }}" class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 text-3xl mb-8 group-hover:scale-110 transition-transform">
                        {!! $category->icon ?? '📚' !!}
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-3 uppercase tracking-tighter">{{ $category->name }}</h3>
                    <p class="text-gray-500 mb-8 line-clamp-2">{{ $category->description }}</p>
                    <div class="flex items-center justify-between text-xs font-black uppercase tracking-widest text-indigo-600">
                        <span>{{ $category->articles_count }} Articles</span>
                        <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Popular Articles --}}
    @if($popularArticles->count() > 0)
    <div class="bg-white py-20 px-6 border-y border-gray-100">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-sm font-black text-indigo-600 uppercase tracking-[0.3em] mb-12 text-center">Popular Guides</h2>
            <div class="space-y-4">
                @foreach($popularArticles as $article)
                    <a href="{{ route('kb.show', $article->slug) }}" class="flex items-center justify-between p-6 bg-gray-50 hover:bg-indigo-50 rounded-2xl transition-colors group">
                        <div class="flex items-center gap-6">
                            <span class="text-2xl opacity-50 group-hover:opacity-100 transition-opacity">💡</span>
                            <span class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $article->title }}</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Footer --}}
    <footer class="py-20 text-center">
        <p class="text-sm font-black text-gray-400 uppercase tracking-widest">© {{ date('Y') }} {{ config('app.name') }} Help Center. All rights reserved.</p>
    </footer>
</body>
</html>
