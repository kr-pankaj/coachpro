<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Simple authorization check for super admin
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }

        $institutes = Institute::all();
        return view('superadmin.index', compact('institutes'));
    }

    public function toggleLifetimeFree(Request $request, Institute $institute)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }

        $institute->is_lifetime_free = !$institute->is_lifetime_free;
        $institute->save();

        return back()->with('success', 'Institute lifetime free status updated successfully.');
    }
}
