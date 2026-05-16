<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $category->name }} | Help Center</title>
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

    <nav class="bg-white/70 backdrop-blur-xl border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('kb.index') }}" class="flex items-center gap-3">
                <x-application-logo class="h-8 w-auto" />
                <span class="font-black text-xl tracking-tighter uppercase">Help Center</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('kb.index') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 uppercase tracking-widest">All Categories</a>
                <a href="/" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 uppercase tracking-widest">Website →</a>
            </div>
        </div>
    </nav>

    <div class="py-20 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-6 mb-8">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-indigo-600 text-4xl shadow-xl shadow-indigo-500/10">
                    {!! $category->icon ?? '📚' !!}
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-gray-900 uppercase">{{ $category->name }}</h1>
                    <p class="text-lg text-gray-500 mt-2">{{ $category->description }}</p>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden divide-y divide-gray-50">
                @forelse($articles as $article)
                    <a href="{{ route('kb.show', $article->slug) }}" class="flex items-center justify-between p-10 hover:bg-gray-50 transition-all group">
                        <div class="flex-1">
                            <h2 class="text-2xl font-black text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{{ $article->title }}</h2>
                            <p class="text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($article->content), 180) }}</p>
                        </div>
                        <svg class="w-8 h-8 text-gray-200 group-hover:text-indigo-600 group-hover:translate-x-2 transition-all ml-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </a>
                @empty
                    <div class="p-20 text-center">
                        <p class="text-gray-400 font-bold uppercase tracking-widest">No articles found in this category yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</body>
</html>
