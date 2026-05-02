<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstituteController extends Controller
{
    public function settings()
    {
        $institute = auth()->user()->institute;
        $profilePct = $institute->profileCompletion();
        return view('institute.settings', compact('institute', 'profilePct'));
    }

    public function updateSettings(Request $request)
    {
        $institute = auth()->user()->institute;

        $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:institutes,slug,' . $institute->id,
            'phone'             => 'nullable|string|max:20',
            'contact_email'     => 'nullable|email|max:255',
            'website'           => 'nullable|url|max:255',
            'description'       => 'nullable|string|max:1000',
            'address'           => 'nullable|string|max:500',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'pincode'           => 'nullable|string|max:10',
            'logo_url'          => 'nullable|url|max:500',
            'brand_color'       => 'nullable|string|max:7',
            'established_year'  => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $institute->update([
            'name'                          => $request->name,
            'slug'                          => Str::slug($request->slug),
            'phone'                         => $request->phone,
            'contact_email'                 => $request->contact_email,
            'website'                       => $request->website,
            'description'                   => $request->description,
            'address'                       => $request->address,
            'city'                          => $request->city,
            'state'                         => $request->state,
            'pincode'                       => $request->pincode,
            'logo_url'                      => $request->logo_url,
            'brand_color'                   => $request->brand_color ?? '#4f46e5',
            'established_year'              => $request->established_year,
            'allow_student_self_registration' => $request->has('allow_student_self_registration'),
        ]);

        return back()->with('success', 'Institute profile updated successfully!');
    }
}
