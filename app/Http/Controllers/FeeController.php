<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $institute = auth()->user()->institute;
        $query = \App\Models\Fee::with('student');

        // Premium Check: Lifetime financial archiving vs 6-month limit
        if (!$institute->isPremium()) {
            $query->where('payment_date', '>=', now()->subMonths(6));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month_year')) {
            $query->where('month_year', $request->month_year);
        }

        $fees = $query->latest('payment_date')->paginate(10)->withQueryString();
        return view('fees.index', compact('fees'));
    }

    public function create()
    {
        $students = \App\Models\Student::all();
        return view('fees.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'total_amount' => 'required|numeric|min:0|max:9999999',
            'amount_paid' => 'nullable|numeric|min:0|max:9999999',
            'discount_amount' => 'nullable|numeric|min:0|max:9999999',
            'payment_date' => 'nullable|date',
            'month_year' => ['required', 'string', 'regex:/^([A-Za-z]+ [0-9]{4}|[0-9]{4}-[0-9]{2})$/'], // Accepts "May 2026" OR "2026-05"
            'payment_method' => 'nullable|string|max:50',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Standardize to human-readable format (e.g., "April 2026")
        $validated['month_year'] = \Carbon\Carbon::parse($validated['month_year'])->format('F Y');

        $feeData = [
            'student_id' => $validated['student_id'],
            'total_amount' => $validated['total_amount'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'paid_amount' => 0, // Will be updated by FeePayment booted event
            'payment_date' => $validated['payment_date'] ?? now()->toDateString(),
            'month_year' => $validated['month_year'],
            'remarks' => $validated['remarks'],
        ];

        $fee = \App\Models\Fee::create($feeData);

        if (($validated['amount_paid'] ?? 0) > 0) {
            $fee->payments()->create([
                'amount' => $validated['amount_paid'],
                'payment_date' => $feeData['payment_date'],
                'payment_method' => $validated['payment_method'],
                'remarks' => 'Initial payment',
            ]);
        }

        if (($validated['amount_paid'] ?? 0) > 0 && $fee->student && $fee->student->user) {
            $fee->student->user->notify(new \App\Notifications\FeeReceipt($fee->fresh()));
        }

        return redirect()->route('fees.show', $fee)->with('success', 'Fee record created successfully.');
    }

    public function show(\App\Models\Fee $fee)
    {
        $fee->load(['student.institute', 'payments']);
        return view('fees.show', compact('fee'));
    }

    public function addPayment(Request $request, \App\Models\Fee $fee)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:9999999',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($validated['amount'] > $fee->due_amount) {
            return back()->with('error', 'Payment amount cannot exceed the due balance.');
        }

        $fee->payments()->create($validated);

        if ($fee->student && $fee->student->user) {
            $fee->student->user->notify(new \App\Notifications\FeeReceipt($fee->fresh()));
        }

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function edit(\App\Models\Fee $fee)
    {
        $students = \App\Models\Student::all();
        return view('fees.edit', compact('fee', 'students'));
    }

    public function update(Request $request, \App\Models\Fee $fee)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'total_amount' => 'required|numeric|min:0|max:9999999',
            'discount_amount' => 'nullable|numeric|min:0|max:9999999',
            'payment_date' => 'nullable|date',
            'month_year' => ['required', 'string', 'regex:/^([A-Za-z]+ [0-9]{4}|[0-9]{4}-[0-9]{2})$/'],
            'remarks' => 'nullable|string|max:500',
        ]);

        $validated['month_year'] = \Carbon\Carbon::parse($validated['month_year'])->format('F Y');

        $fee->update($validated);

        return redirect()->route('fees.show', $fee)->with('success', 'Fee record updated successfully.');
    }

    public function destroy(\App\Models\Fee $fee)
    {
        $fee->delete();
        return redirect()->route('fees.index')->with('success', 'Fee record deleted successfully.');
    }

    public function receipt(\App\Models\Fee $fee)
    {
        // Ensure only Paid fees can have receipts
        if ($fee->status === 'pending') {
            abort(403, 'Receipts are only available for partial or full payments.');
        }

        // Security check
        $user = auth()->user();
        if ($user->role === 'student') {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if (!$student || $student->id !== $fee->student_id) {
                abort(403, 'Unauthorized action.');
            }
        } elseif ($user->role === 'admin') {
            if ($user->institute_id !== $fee->student->institute_id) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        $institute = $fee->student->institute;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('fees.receipt', compact('fee', 'institute'));
        
        return $pdf->download('Receipt-' . str_pad($fee->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function share(string $token)
    {
        $fee = \App\Models\Fee::where('share_token', $token)->firstOrFail();

        if ($fee->status === 'pending') {
            abort(403, 'Receipts are only available for partial or full payments.');
        }

        $institute = $fee->student->institute;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('fees.receipt', compact('fee', 'institute'));
        
        return $pdf->stream('Receipt-' . str_pad($fee->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
