<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::latest()->paginate(10);
        return view('superadmin.badges.index', compact('badges'));
    }

    public function create()
    {
        return view('superadmin.badges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'icon' => 'required|string',
            'color' => 'required|string',
            'requirement_type' => 'required|string',
            'requirement_value' => 'required|integer|min:0',
            'is_secret' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        Badge::create($validated);

        return redirect()->route('superadmin.badges.index')->with('success', 'Badge created successfully.');
    }

    public function edit(Badge $badge)
    {
        return view('superadmin.badges.edit', compact('badge'));
    }

    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'icon' => 'required|string',
            'color' => 'required|string',
            'requirement_type' => 'required|string',
            'requirement_value' => 'required|integer|min:0',
            'is_secret' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $badge->update($validated);

        return redirect()->route('superadmin.badges.index')->with('success', 'Badge updated successfully.');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();
        return redirect()->route('superadmin.badges.index')->with('success', 'Badge deleted successfully.');
    }
}
