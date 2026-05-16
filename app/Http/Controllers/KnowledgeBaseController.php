<?php

namespace App\Http\Controllers;

use App\Models\KbArticle;
use App\Models\KbCategory;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $categories = KbCategory::withCount(['articles' => function($q) {
            $q->where('is_published', true)->where('is_internal_only', false);
        }])->orderBy('order')->get();

        $popularArticles = KbArticle::where('is_published', true)
            ->where('is_internal_only', false)
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('kb.index', compact('categories', 'popularArticles'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $articles = KbArticle::search($query)
            ->where('is_published', true)
            ->where('is_internal_only', false)
            ->take(10)
            ->get()
            ->map(function($article) {
                return [
                    'title' => $article->title,
                    'url' => route('kb.show', $article->slug),
                    'category' => $article->category->name ?? 'Uncategorized'
                ];
            });

        return response()->json($articles);
    }

    public function category($category_slug)
    {
        $category = KbCategory::where('slug', $category_slug)->firstOrFail();
        $articles = $category->articles()
            ->where('is_published', true)
            ->where('is_internal_only', false)
            ->latest()
            ->paginate(10);

        return view('kb.category', compact('category', 'articles'));
    }

    public function show($article_slug)
    {
        $article = KbArticle::where('slug', $article_slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Check internal access
        if ($article->is_internal_only && (!auth()->check() || auth()->user()->role !== 'superadmin')) {
            abort(403, 'This article is for internal use only.');
        }

        $article->increment('views_count');

        $relatedArticles = KbArticle::where('kb_category_id', $article->kb_category_id)
            ->where('id', '!=', $article->id)
            ->where('is_published', true)
            ->where('is_internal_only', false)
            ->take(3)
            ->get();

        return view('kb.show', compact('article', 'relatedArticles'));
    }

    public function feedback(Request $request, KbArticle $article)
    {
        $request->validate([
            'is_helpful' => 'required|boolean'
        ]);

        \App\Models\KbArticleFeedback::create([
            'kb_article_id' => $article->id,
            'is_helpful' => $request->is_helpful,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip()
        ]);

        return response()->json(['message' => 'Thank you for your feedback!']);
    }
}
