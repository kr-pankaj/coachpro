<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} | Help Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif !important; }
        .kb-content h2 { font-size: 1.875rem; font-weight: 900; color: #111827; margin-top: 2.5rem; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: -0.025em; }
        .kb-content h3 { font-size: 1.5rem; font-weight: 800; color: #111827; margin-top: 2rem; margin-bottom: 1rem; }
        .kb-content p { font-size: 1.125rem; line-height: 1.8; color: #4b5563; margin-bottom: 1.5rem; }
        .kb-content ul, .kb-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
        .kb-content li { font-size: 1.125rem; line-height: 1.8; color: #4b5563; margin-bottom: 0.75rem; }
        .kb-content strong { color: #111827; font-weight: 700; }
        .kb-content code { background: #f3f4f6; padding: 0.2rem 0.4rem; rounded: 0.375rem; font-family: monospace; font-size: 0.875em; color: #e11d48; }
        .kb-content blockquote { border-left: 4px solid #6366f1; background: #f5f3ff; padding: 1.5rem 2rem; border-radius: 1rem; margin-bottom: 1.5rem; font-style: italic; color: #4f46e5; }
        .kb-content img { border-radius: 2rem; margin: 3rem 0; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="antialiased text-gray-900 bg-white">

    <nav class="bg-white/70 backdrop-blur-xl border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('kb.index') }}" class="flex items-center gap-3">
                <x-application-logo class="h-8 w-auto" />
                <span class="font-black text-xl tracking-tighter uppercase">Help Center</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('kb.index') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 uppercase tracking-widest">Back to Help</a>
            </div>
        </div>
    </nav>

    <div class="py-20 px-6">
        <div class="max-w-4xl mx-auto" x-data="{ feedbackSent: false }">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-3 mb-10 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 transition-colors">Help Home</a>
                <span>/</span>
                <a href="{{ route('kb.category', $article->category->slug) }}" class="hover:text-indigo-600 transition-colors">{{ $article->category->name }}</a>
                <span>/</span>
                <span class="text-gray-900">{{ $article->title }}</span>
            </nav>

            <header class="mb-16">
                <h1 class="text-5xl md:text-6xl font-black tracking-tighter text-gray-900 mb-6 uppercase">{{ $article->title }}</h1>
                <div class="flex items-center gap-6 text-sm text-gray-500 font-medium">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                        Last updated {{ $article->updated_at->format('M d, Y') }}
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                        {{ $article->views_count }} views
                    </span>
                </div>
            </header>

            <article class="kb-content prose prose-indigo max-w-none">
                {!! $article->content !!}
            </article>

            {{-- Feedback Section --}}
            <div class="mt-20 p-12 bg-gray-50 rounded-[3rem] text-center overflow-hidden relative">
                <div x-show="!feedbackSent" x-transition>
                    <h3 class="text-2xl font-black text-gray-900 mb-4 uppercase tracking-tighter">Was this article helpful?</h3>
                    <div class="flex items-center justify-center gap-6 mt-8">
                        <button @click="sendFeedback(true)" class="px-8 py-4 bg-white border border-gray-100 rounded-2xl font-bold text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all flex items-center gap-3">
                            <span class="text-xl">👍</span> Yes, thanks!
                        </button>
                        <button @click="sendFeedback(false)" class="px-8 py-4 bg-white border border-gray-100 rounded-2xl font-bold text-gray-600 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all flex items-center gap-3">
                            <span class="text-xl">👎</span> Not really
                        </button>
                    </div>
                </div>
                <div x-show="feedbackSent" x-transition class="py-4">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Thank you!</h3>
                    <p class="text-gray-500 font-medium">Your feedback helps us improve our guides.</p>
                </div>
            </div>

            <script>
                function sendFeedback(helpful) {
                    fetch('{{ route('kb.feedback', $article) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ is_helpful: helpful })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.feedbackSent = true;
                    });
                }
            </script>

            {{-- Related Articles --}}
            @if($relatedArticles->count() > 0)
            <div class="mt-20">
                <h3 class="text-sm font-black text-indigo-600 uppercase tracking-[0.3em] mb-10">Related Guides</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $rel)
                        <a href="{{ route('kb.show', $rel->slug) }}" class="p-8 bg-white border border-gray-100 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all group">
                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-3">{{ $rel->title }}</h4>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Read More →</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <footer class="py-20 bg-gray-50 border-t border-gray-100 text-center">
        <p class="text-sm font-black text-gray-400 uppercase tracking-widest">Still need help? <a href="/contact" class="text-indigo-600 hover:underline">Contact our support team</a></p>
    </footer>
</body>
</html>
