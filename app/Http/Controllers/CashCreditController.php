<?php

namespace App\Http\Controllers;

use App\Models\CashCreditEntry;
use App\Models\DailySale;
use App\Models\SalesRep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashCreditController extends Controller
{
    public function index(Request $request): View
    {
        $summaries = CashCreditEntry::query()
            ->select('entry_date')
            ->selectRaw('SUM(sales_amount) as total_sales, SUM(collection) as total_collection, SUM(credit) as total_credit, SUM(credit_collection) as total_credit_collection, SUM(route_shortage) as total_shortage')
            ->groupBy('entry_date')
            ->orderByDesc('entry_date')
            ->paginate(20);

        return view('cash_credit.index', compact('summaries'));
    }

    public function entry(Request $request): View
    {
        $data = $request->validate([
            'entry_date' => ['nullable', 'date'],
        ]);

        $entryDate = $data['entry_date'] ?? now()->format('Y-m-d');

        $salesReps = SalesRep::where('is_active', true)->orderBy('name')->get();

        $rows = $salesReps->map(function (SalesRep $rep) use ($entryDate) {
            $existing = CashCreditEntry::where('entry_date', $entryDate)
                ->where('sales_rep_id', $rep->id)
                ->first();

            $salesAmount = DailySale::where('sale_date', $entryDate)
                ->where('sales_rep_id', $rep->id)
                ->sum('net_amount');

            $openingBalance = $this->previousClosingBalance($rep, $entryDate);

            return [
                'rep' => $rep,
                'existing' => $existing,
                'sales_amount' => $salesAmount,
                'opening_balance' => $openingBalance,
            ];
        });

        return view('cash_credit.entry', [
            'entryDate' => $entryDate,
            'rows' => $rows,
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entry_date' => ['required', 'date'],
            'rows' => ['required', 'array'],
            'rows.*.collection' => ['nullable', 'numeric', 'min:0'],
            'rows.*.credit_collection' => ['nullable', 'numeric', 'min:0'],
            'rows.*.route_shortage' => ['nullable', 'numeric'],
        ]);

        $entryDate = $validated['entry_date'];

        DB::transaction(function () use ($validated, $entryDate) {
            foreach ($validated['rows'] as $salesRepId => $row) {
                $rep = SalesRep::find($salesRepId);
                if (! $rep) {
                    continue;
                }

                $salesAmount = DailySale::where('sale_date', $entryDate)
                    ->where('sales_rep_id', $salesRepId)
                    ->sum('net_amount');

                $collection = (float) ($row['collection'] ?? 0);
                $creditCollection = (float) ($row['credit_collection'] ?? 0);
                $routeShortage = (float) ($row['route_shortage'] ?? 0);

                $credit = max(0, $salesAmount - $collection);
                $openingBalance = $this->previousClosingBalance($rep, $entryDate);
                $routeCredit = $openingBalance + $credit - $creditCollection;

                CashCreditEntry::updateOrCreate(
                    [
                        'entry_date' => $entryDate,
                        'sales_rep_id' => $salesRepId,
                    ],
                    [
                        'sales_amount' => $salesAmount,
                        'collection' => $collection,
                        'credit' => $credit,
                        'credit_collection' => $creditCollection,
                        'opening_credit_balance' => $openingBalance,
                        'route_credit' => $routeCredit,
                        'route_shortage' => $routeShortage,
                    ]
                );
            }
        });

        return redirect()
            ->route('cash-credit.entry', ['entry_date' => $entryDate])
            ->with('success', 'Cash & credit entry saved for '.$entryDate.'.');
    }

    private function previousClosingBalance(SalesRep $rep, string $entryDate): float
    {
        $previous = CashCreditEntry::where('sales_rep_id', $rep->id)
            ->where('entry_date', '<', $entryDate)
            ->orderByDesc('entry_date')
            ->first();

        return $previous ? (float) $previous->route_credit : (float) $rep->opening_credit;
    }
}
