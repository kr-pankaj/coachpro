<?php
 
namespace App\Http\Controllers;
 
use App\Models\KbCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
 
class KbCategoryController extends Controller
{
    public function index()
    {
        $categories = KbCategory::withCount('articles')->orderBy('order')->get();
        return view('superadmin.kb.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'order' => 'integer'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        KbCategory::create($validated);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, KbCategory $kbCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'order' => 'integer'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $kbCategory->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(KbCategory $kbCategory)
    {
        $kbCategory->delete();
        return back()->with('success', 'Category removed.');
    }
}
