<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\CashCreditEntry;
use App\Models\Expense;
use App\Models\SupplierPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashBoxController extends Controller
{
    public function index(Request $request): View
    {
        $boxes = CashBox::orderByDesc('cash_date')->paginate(20);

        return view('cash_box.index', compact('boxes'));
    }

    public function entry(Request $request): View
    {
        $cashDate = $request->get('cash_date', now()->format('Y-m-d'));

        $existing = CashBox::where('cash_date', $cashDate)->first();

        $openingBalance = $existing?->opening_balance ?? $this->previousClosing($cashDate);

        $totals = $this->computeTotals($cashDate);

        return view('cash_box.entry', [
            'cashDate' => $cashDate,
            'existing' => $existing,
            'openingBalance' => $openingBalance,
            'totals' => $totals,
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cash_date' => ['required', 'date'],
            'opening_balance' => ['required', 'numeric'],
            'adjustment' => ['nullable', 'numeric'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $cashDate = $data['cash_date'];
        $totals = $this->computeTotals($cashDate);
        $adjustment = (float) ($data['adjustment'] ?? 0);

        $closingBalance = $data['opening_balance']
            + $totals['collection'] + $totals['credit_collection']
            - $totals['expense'] - $totals['supplier_payment']
            + $adjustment;

        CashBox::updateOrCreate(
            ['cash_date' => $cashDate],
            [
                'opening_balance' => $data['opening_balance'],
                'total_collection' => $totals['collection'],
                'total_credit_collection' => $totals['credit_collection'],
                'total_expense' => $totals['expense'],
                'total_supplier_payment' => $totals['supplier_payment'],
                'adjustment' => $adjustment,
                'closing_balance' => $closingBalance,
                'note' => $data['note'] ?? null,
            ]
        );

        return redirect()
            ->route('cash-box.entry', ['cash_date' => $cashDate])
            ->with('success', 'Cash box closed for '.$cashDate.'.');
    }

    /**
     * @return array{collection: float, credit_collection: float, expense: float, supplier_payment: float}
     */
    private function computeTotals(string $cashDate): array
    {
        $credit = CashCreditEntry::where('entry_date', $cashDate)
            ->selectRaw('SUM(collection) as collection, SUM(credit_collection) as credit_collection')
            ->first();

        return [
            'collection' => (float) ($credit->collection ?? 0),
            'credit_collection' => (float) ($credit->credit_collection ?? 0),
            'expense' => (float) Expense::where('expense_date', $cashDate)->sum('amount'),
            'supplier_payment' => (float) SupplierPayment::where('payment_date', $cashDate)->sum('amount'),
        ];
    }

    private function previousClosing(string $cashDate): float
    {
        $previous = CashBox::where('cash_date', '<', $cashDate)->orderByDesc('cash_date')->first();

        return $previous ? (float) $previous->closing_balance : 0;
    }
}
