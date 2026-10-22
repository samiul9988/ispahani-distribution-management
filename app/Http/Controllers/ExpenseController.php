<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $expenses = Expense::whereBetween('expense_date', [$from, $to])
            ->orderByDesc('expense_date')
            ->paginate(20);

        $total = Expense::whereBetween('expense_date', [$from, $to])->sum('amount');

        return view('expenses.index', compact('expenses', 'from', 'to', 'total'));
    }

    public function create(): View
    {
        return view('expenses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'expense_date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}
