<?php

namespace App\Http\Controllers;

use App\Models\CashCreditEntry;
use App\Models\DailySale;
use App\Models\Product;
use App\Models\SalesRep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DailySaleController extends Controller
{
    public function index(Request $request): View
    {
        $salesReps = SalesRep::orderBy('name')->get();

        $summaries = DailySale::query()
            ->select('sale_date', 'sales_rep_id')
            ->selectRaw('SUM(total_taka) as total_taka, SUM(discount) as total_discount, SUM(net_amount) as total_net')
            ->groupBy('sale_date', 'sales_rep_id')
            ->with('salesRep')
            ->orderByDesc('sale_date')
            ->paginate(20);

        return view('daily_sales.index', compact('summaries', 'salesReps'));
    }

    public function entry(Request $request): View
    {
        $data = $request->validate([
            'sale_date' => ['nullable', 'date'],
            'sales_rep_id' => ['nullable', 'exists:sales_reps,id'],
        ]);

        $saleDate = $data['sale_date'] ?? now()->format('Y-m-d');
        $salesRepId = $data['sales_rep_id'] ?? null;

        $salesReps = SalesRep::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        $existing = [];
        $existingCredit = null;
        $openingBalance = 0;

        if ($salesRepId) {
            $existing = DailySale::where('sale_date', $saleDate)
                ->where('sales_rep_id', $salesRepId)
                ->get()
                ->keyBy('product_id');

            $existingCredit = CashCreditEntry::where('entry_date', $saleDate)
                ->where('sales_rep_id', $salesRepId)
                ->first();

            $rep = $salesReps->firstWhere('id', (int) $salesRepId);
            $openingBalance = $rep ? $this->previousClosingBalance($rep, $saleDate) : 0;
        }

        return view('daily_sales.entry', [
            'saleDate' => $saleDate,
            'salesRepId' => $salesRepId,
            'salesReps' => $salesReps,
            'products' => $products,
            'existing' => $existing,
            'existingCredit' => $existingCredit,
            'openingBalance' => $openingBalance,
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sale_date' => ['required', 'date'],
            'sales_rep_id' => ['required', 'exists:sales_reps,id'],
            'rows' => ['required', 'array'],
            'rows.*.loading_ctn' => ['nullable', 'numeric', 'min:0'],
            'rows.*.loading_pcs' => ['nullable', 'numeric', 'min:0'],
            'rows.*.unloading_pcs' => ['nullable', 'numeric', 'min:0'],
            'rows.*.discount' => ['nullable', 'numeric', 'min:0'],
            'collection' => ['nullable', 'numeric', 'min:0'],
            'credit_collection' => ['nullable', 'numeric', 'min:0'],
            'route_shortage' => ['nullable', 'numeric'],
        ]);

        $saleDate = $validated['sale_date'];
        $salesRepId = $validated['sales_rep_id'];

        DB::transaction(function () use ($validated, $saleDate, $salesRepId) {
            foreach ($validated['rows'] as $productId => $row) {
                $product = Product::find($productId);
                if (! $product) {
                    continue;
                }

                $loadingCtn = (float) ($row['loading_ctn'] ?? 0);
                $loadingPcs = (float) ($row['loading_pcs'] ?? 0);
                $unloadingPcs = (float) ($row['unloading_pcs'] ?? 0);
                $discount = (float) ($row['discount'] ?? 0);

                $totalLoadedPcs = ($loadingCtn * $product->pic) + $loadingPcs;
                $salesPcs = max(0, $totalLoadedPcs - $unloadingPcs);
                $totalTaka = $salesPcs * $product->price;
                $netAmount = $totalTaka - $discount;

                if ($totalLoadedPcs == 0 && $unloadingPcs == 0 && $discount == 0) {
                    DailySale::where('sale_date', $saleDate)
                        ->where('sales_rep_id', $salesRepId)
                        ->where('product_id', $productId)
                        ->delete();

                    continue;
                }

                DailySale::updateOrCreate(
                    [
                        'sale_date' => $saleDate,
                        'sales_rep_id' => $salesRepId,
                        'product_id' => $productId,
                    ],
                    [
                        'loading_ctn' => $loadingCtn,
                        'loading_pcs' => $loadingPcs,
                        'total_loaded_pcs' => $totalLoadedPcs,
                        'unloading_pcs' => $unloadingPcs,
                        'sales_pcs' => $salesPcs,
                        'price_at_sale' => $product->price,
                        'total_taka' => $totalTaka,
                        'discount' => $discount,
                        'net_amount' => $netAmount,
                    ]
                );
            }

            $rep = SalesRep::find($salesRepId);
            $salesAmount = DailySale::where('sale_date', $saleDate)
                ->where('sales_rep_id', $salesRepId)
                ->sum('net_amount');

            $collection = (float) ($validated['collection'] ?? 0);
            $creditCollection = (float) ($validated['credit_collection'] ?? 0);
            $routeShortage = (float) ($validated['route_shortage'] ?? 0);

            $credit = max(0, $salesAmount - $collection);
            $openingBalance = $this->previousClosingBalance($rep, $saleDate);
            $routeCredit = $openingBalance + $credit - $creditCollection;

            CashCreditEntry::updateOrCreate(
                [
                    'entry_date' => $saleDate,
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
        });

        return redirect()
            ->route('daily-sales.entry', ['sale_date' => $saleDate, 'sales_rep_id' => $salesRepId])
            ->with('success', 'Sale entry saved for '.$saleDate.' — sales, stock and cash/credit all updated.');
    }

    private function previousClosingBalance(SalesRep $rep, string $saleDate): float
    {
        $previous = CashCreditEntry::where('sales_rep_id', $rep->id)
            ->where('entry_date', '<', $saleDate)
            ->orderByDesc('entry_date')
            ->first();

        return $previous ? (float) $previous->route_credit : (float) $rep->opening_credit;
    }
}
