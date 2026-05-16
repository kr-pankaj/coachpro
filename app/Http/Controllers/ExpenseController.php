<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Batch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $institute = auth()->user()->institute;

        // Filters
        $month    = $request->get('month', now()->format('Y-m'));
        $category = $request->get('category', '');
        $batchId  = $request->get('batch_id', '');

        $query = Expense::where('institute_id', $institute->id)
            ->with('batch')
            ->orderByDesc('expense_date');

        if ($month) {
            [$y, $m] = explode('-', $month);
            $query->whereYear('expense_date', $y)->whereMonth('expense_date', $m);
        }
        if ($category) {
            $query->where('category', $category);
        }
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        $expenses = $query->paginate(20)->withQueryString();

        // Summary stats for the filtered period
        $totalThisMonth  = Expense::where('institute_id', $institute->id)
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $totalAllTime = Expense::where('institute_id', $institute->id)->sum('amount');

        // Category breakdown for current month
        $breakdown = Expense::where('institute_id', $institute->id)
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $batches    = Batch::where('institute_id', $institute->id)->get();
        $categories = Expense::$categories;

        return view('expenses.index', compact(
            'expenses', 'batches', 'categories',
            'totalThisMonth', 'totalAllTime', 'breakdown',
            'month', 'category', 'batchId'
        ));
    }

    public function create()
    {
        $institute  = auth()->user()->institute;
        $batches    = Batch::where('institute_id', $institute->id)->get();
        $categories = Expense::$categories;
        return view('expenses.create', compact('batches', 'categories'));
    }

    public function store(Request $request)
    {
        $institute = auth()->user()->institute;

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:' . implode(',', array_keys(Expense::$categories)),
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'batch_id'     => 'nullable|exists:batches,id',
            'notes'        => 'nullable|string|max:1000',
        ]);

        // Ensure batch belongs to this institute
        if (!empty($validated['batch_id'])) {
            $batch = Batch::where('id', $validated['batch_id'])
                ->where('institute_id', $institute->id)
                ->firstOrFail();
        }

        Expense::create(array_merge($validated, ['institute_id' => $institute->id]));

        return redirect()->route('expenses.index')
            ->with('success', 'Expense of ₹' . number_format($validated['amount']) . ' recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $this->authorizeExpense($expense);
        $institute  = auth()->user()->institute;
        $batches    = Batch::where('institute_id', $institute->id)->get();
        $categories = Expense::$categories;
        return view('expenses.edit', compact('expense', 'batches', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorizeExpense($expense);
        $institute = auth()->user()->institute;

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:' . implode(',', array_keys(Expense::$categories)),
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'batch_id'     => 'nullable|exists:batches,id',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorizeExpense($expense);
        $expense->delete();
        return back()->with('success', 'Expense deleted.');
    }

    private function authorizeExpense(Expense $expense): void
    {
        if ($expense->institute_id !== auth()->user()->institute->id) {
            abort(403);
        }
    }
}
