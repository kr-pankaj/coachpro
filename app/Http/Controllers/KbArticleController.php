<?php

namespace App\Http\Controllers;

use App\Models\KbArticle;
use App\Models\KbCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KbArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = KbArticle::with('category')
            ->withCount(['feedback as helpful_count' => function($q) {
                $q->where('is_helpful', true);
            }])
            ->withCount('feedback');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('kb_category_id', $request->category_id);
        }

        $articles = $query->latest()->paginate(15);
        $categories = KbCategory::orderBy('name')->get();

        return view('superadmin.kb.articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = KbCategory::orderBy('name')->get();
        return view('superadmin.kb.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kb_category_id' => 'required|exists:kb_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'is_internal_only' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['is_internal_only'] = $request->has('is_internal_only');

        KbArticle::create($validated);

        return redirect()->route('superadmin.kb-articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(KbArticle $kbArticle)
    {
        $categories = KbCategory::orderBy('name')->get();
        return view('superadmin.kb.articles.edit', compact('kbArticle', 'categories'));
    }

    public function update(Request $request, KbArticle $kbArticle)
    {
        $validated = $request->validate([
            'kb_category_id' => 'required|exists:kb_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'is_internal_only' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['is_internal_only'] = $request->has('is_internal_only');

        $kbArticle->update($validated);

        return redirect()->route('superadmin.kb-articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(KbArticle $kbArticle)
    {
        $kbArticle->delete();
        return back()->with('success', 'Article removed.');
    }
}
