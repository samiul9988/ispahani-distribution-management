<?php

namespace App\Http\Controllers;

use App\Exports\ArrayExport;
use App\Models\CashCreditEntry;
use App\Models\DailySale;
use App\Models\Lifting;
use App\Models\Product;
use App\Models\SalesRep;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function dailySales(Request $request): View|Response
    {
        [$from, $to, $repId] = $this->dateRangeAndRep($request);

        $query = DailySale::query()
            ->selectRaw('sale_date, sales_rep_id, SUM(total_taka) as total_taka, SUM(discount) as total_discount, SUM(net_amount) as total_net')
            ->whereBetween('sale_date', [$from, $to])
            ->when($repId, fn ($q) => $q->where('sales_rep_id', $repId))
            ->groupBy('sale_date', 'sales_rep_id')
            ->orderBy('sale_date')
            ->with('salesRep')
            ->get();

        $rows = $query->map(fn ($r) => [
            'Date' => $r->sale_date->format('Y-m-d'),
            'Sales Rep' => $r->salesRep?->name,
            'Total Taka' => round($r->total_taka, 2),
            'Discount' => round($r->total_discount, 2),
            'Net Amount' => round($r->total_net, 2),
        ]);

        $headings = ['Date', 'Sales Rep', 'Total Taka', 'Discount', 'Net Amount'];
        $salesReps = SalesRep::orderBy('name')->get();

        if ($export = $this->maybeExport($request, $rows, $headings, 'daily-sales-report')) {
            return $export;
        }

        return view('reports.daily_sales', compact('rows', 'from', 'to', 'repId', 'salesReps'));
    }

    public function srWise(Request $request): View|Response
    {
        [$from, $to] = $this->dateRangeAndRep($request);

        $rows = SalesRep::orderBy('name')->get()->map(function (SalesRep $rep) use ($from, $to) {
            $sales = DailySale::where('sales_rep_id', $rep->id)->whereBetween('sale_date', [$from, $to]);
            $credit = CashCreditEntry::where('sales_rep_id', $rep->id)->whereBetween('entry_date', [$from, $to]);

            $lastCredit = CashCreditEntry::where('sales_rep_id', $rep->id)
                ->whereBetween('entry_date', [$from, $to])
                ->orderByDesc('entry_date')
                ->first();

            return [
                'Sales Rep' => $rep->name,
                'Market' => $rep->market?->name,
                'Total Sales' => round($sales->sum('total_taka'), 2),
                'Total Discount' => round($sales->sum('discount'), 2),
                'Net Sales' => round($sales->sum('net_amount'), 2),
                'Collection' => round($credit->sum('collection'), 2),
                'Credit Collection' => round($credit->sum('credit_collection'), 2),
                'Closing Route Credit' => round($lastCredit?->route_credit ?? $rep->opening_credit, 2),
            ];
        });

        $headings = ['Sales Rep', 'Market', 'Total Sales', 'Total Discount', 'Net Sales', 'Collection', 'Credit Collection', 'Closing Route Credit'];

        if ($export = $this->maybeExport($request, $rows, $headings, 'sr-wise-report')) {
            return $export;
        }

        return view('reports.sr_wise', compact('rows', 'from', 'to'));
    }

    public function productWise(Request $request): View|Response
    {
        [$from, $to] = $this->dateRangeAndRep($request);

        $rows = Product::orderBy('name')->get()->map(function (Product $product) use ($from, $to) {
            $sales = DailySale::where('product_id', $product->id)->whereBetween('sale_date', [$from, $to]);

            return [
                'Product' => $product->name,
                'Price' => round($product->price, 2),
                'Sales Qty (pcs)' => round($sales->sum('sales_pcs'), 2),
                'Total Taka' => round($sales->sum('total_taka'), 2),
                'Discount' => round($sales->sum('discount'), 2),
                'Net Amount' => round($sales->sum('net_amount'), 2),
            ];
        })->filter(fn ($r) => $r['Sales Qty (pcs)'] > 0)->values();

        $headings = ['Product', 'Price', 'Sales Qty (pcs)', 'Total Taka', 'Discount', 'Net Amount'];

        if ($export = $this->maybeExport($request, $rows, $headings, 'product-wise-report')) {
            return $export;
        }

        return view('reports.product_wise', compact('rows', 'from', 'to'));
    }

    public function stock(Request $request): View|Response
    {
        $asOf = $request->get('as_of', now()->format('Y-m-d'));

        $liftedTotals = Lifting::where('lifting_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(total_lifted_pcs) as pcs, SUM(value) as value')
            ->groupBy('product_id')->get()->keyBy('product_id');

        $soldTotals = DailySale::where('sale_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(sales_pcs) as pcs')
            ->groupBy('product_id')->get()->keyBy('product_id');

        $rows = Product::orderBy('name')->get()->map(function (Product $product) use ($liftedTotals, $soldTotals) {
            $opening = ($product->opening_stock_ctn * $product->pic) + $product->opening_stock_pcs;
            $lifted = $liftedTotals->get($product->id)?->pcs ?? 0;
            $sold = $soldTotals->get($product->id)?->pcs ?? 0;
            $closing = $opening + $lifted - $sold;

            return [
                'Product' => $product->name,
                'Opening (pcs)' => round($opening, 2),
                'Lifted (pcs)' => round($lifted, 2),
                'Sold (pcs)' => round($sold, 2),
                'Closing (pcs)' => round($closing, 2),
                'Closing (ctn)' => $product->pic > 0 ? round($closing / $product->pic, 2) : 0,
                'Stock Value' => round($closing * $product->price, 2),
            ];
        });

        $headings = ['Product', 'Opening (pcs)', 'Lifted (pcs)', 'Sold (pcs)', 'Closing (pcs)', 'Closing (ctn)', 'Stock Value'];

        if ($export = $this->maybeExport($request, $rows, $headings, 'stock-report')) {
            return $export;
        }

        return view('reports.stock', compact('rows', 'asOf'));
    }

    public function credit(Request $request): View|Response
    {
        [$from, $to, $repId] = $this->dateRangeAndRep($request);

        $entries = CashCreditEntry::query()
            ->whereBetween('entry_date', [$from, $to])
            ->when($repId, fn ($q) => $q->where('sales_rep_id', $repId))
            ->with('salesRep')
            ->orderBy('entry_date')
            ->get();

        $rows = $entries->map(fn ($e) => [
            'Date' => $e->entry_date->format('Y-m-d'),
            'Sales Rep' => $e->salesRep?->name,
            'Sales' => round($e->sales_amount, 2),
            'Collection' => round($e->collection, 2),
            'Credit' => round($e->credit, 2),
            'Credit Collection' => round($e->credit_collection, 2),
            'Route Credit' => round($e->route_credit, 2),
            'Shortage' => round($e->route_shortage, 2),
        ]);

        $headings = ['Date', 'Sales Rep', 'Sales', 'Collection', 'Credit', 'Credit Collection', 'Route Credit', 'Shortage'];
        $salesReps = SalesRep::orderBy('name')->get();

        if ($export = $this->maybeExport($request, $rows, $headings, 'credit-report')) {
            return $export;
        }

        return view('reports.credit', compact('rows', 'from', 'to', 'repId', 'salesReps'));
    }

    /**
     * @return array{0: string, 1: string, 2: int|null}
     */
    private function dateRangeAndRep(Request $request): array
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));
        $repId = $request->get('sales_rep_id') ?: null;

        return [$from, $to, $repId];
    }

    /**
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $rows
     * @param array<int, string> $headings
     */
    private function maybeExport(Request $request, $rows, array $headings, string $filename): Response|null
    {
        $format = $request->get('format');

        if ($format === 'xlsx') {
            return Excel::download(new ArrayExport($rows->toArray(), $headings), $filename.'.xlsx');
        }

        if ($format === 'pdf') {
            $html = ViewFacade::make('reports.pdf', [
                'title' => str($filename)->replace('-', ' ')->title(),
                'headings' => $headings,
                'rows' => $rows,
            ])->render();

            return Pdf::loadHTML($html)->download($filename.'.pdf');
        }

        return null;
    }
}
