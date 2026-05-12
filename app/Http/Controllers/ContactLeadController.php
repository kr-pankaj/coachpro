<?php

namespace App\Http\Controllers;

use App\Models\ContactLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactLeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|max:100',
            'phone'            => 'nullable|string|max:20',
            'institute_name'   => 'nullable|string|max:150',
            'city'             => 'nullable|string|max:100',
            'plan_interest'    => 'nullable|in:monthly,six_month,custom',
            'message'          => 'nullable|string|max:1000',
        ]);

        $lead = ContactLead::create($validated);

        // Notify super admin
        $adminEmail = config('mail.contact_lead_to', config('mail.from.address'));
        try {
            Mail::send('emails.contact-lead-admin', ['lead' => $lead], function ($m) use ($adminEmail, $lead) {
                $m->to($adminEmail)
                  ->subject('🔔 New Contact Lead: ' . $lead->name . ' (' . ($lead->institute_name ?? 'N/A') . ')');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Contact lead admin mail failed: ' . $e->getMessage());
        }

        // Confirm to user
        try {
            Mail::send('emails.contact-lead-user', ['lead' => $lead], function ($m) use ($lead) {
                $m->to($lead->email)
                  ->subject('We received your enquiry – QuonixAI');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Contact lead user mail failed: ' . $e->getMessage());
        }

        return back()->with('contact_success', 'Thank you, ' . $lead->name . '! We will get back to you within 24 hours.');
    }

    // Super Admin: list all leads
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $leads = ContactLead::orderByDesc('created_at')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->paginate(20);

        return view('superadmin.contact-leads', compact('leads'));
    }

    // Super Admin: update status / notes
    public function update(Request $request, ContactLead $contactLead)
    {
        if (auth()->user()->role !== 'superadmin') abort(403);

        $contactLead->update($request->validate([
            'status'       => 'required|in:new,contacted,converted,closed',
            'admin_notes'  => 'nullable|string|max:2000',
        ]));

        return back()->with('success', 'Lead updated.');
    }
}
