<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\User;
use App\Models\Student;
use App\Models\Fee;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }

        $institutes = Institute::withCount('students')->get();
        
        // Global Stats
        $stats = [
            'total_institutes' => $institutes->count(),
            'total_students'   => Student::count(),
            'total_revenue'    => Fee::where('status', 'paid')->sum('amount'),
            'new_institutes'   => Institute::where('created_at', '>=', today()->subDays(7))->count(),
        ];

        // Platform Growth (Institutes per Month)
        $growth = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::today()->subMonths($i);
            $growth['labels'][] = $date->format('M');
            $growth['data'][]   = Institute::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
        }

        $settings = \App\Models\Setting::pluck('value', 'key');

        return view('superadmin.index', compact('institutes', 'stats', 'growth', 'settings'));
    }

    public function impersonate(Institute $institute)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $owner = User::where('institute_id', $institute->id)->where('role', 'admin')->first();
        
        if (!$owner) {
            return back()->with('error', 'No admin found for this institute.');
        }

        // Store current superadmin ID in session to allow "returning"
        session(['impersonated_by' => auth()->id()]);
        
        auth()->login($owner);

        return redirect()->route('dashboard')->with('success', 'You are now impersonating ' . $institute->name);
    }

    public function broadcast(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,urgent',
        ]);

        \App\Models\Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'institute_id' => null, // Global
        ]);

        return back()->with('success', 'Global broadcast sent successfully to all institutes.');
    }

    public function updateSettings(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Platform settings updated successfully.');
    }

    public function stopImpersonating()
    {
        if (!session()->has('impersonated_by')) {
            return redirect()->route('dashboard');
        }

        $superAdmin = User::find(session('impersonated_by'));
        session()->forget('impersonated_by');
        
        auth()->login($superAdmin);

        return redirect()->route('superadmin.index')->with('success', 'Returned to Super Admin dashboard.');
    }

    public function toggleLifetimeFree(Request $request, Institute $institute)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }

        $institute->is_lifetime_free = !$institute->is_lifetime_free;
        $institute->save();

        return back()->with('success', 'Institute status updated.');
    }
}
