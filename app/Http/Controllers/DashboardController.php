<?php

namespace App\Http\Controllers;

use App\Models\CashCreditEntry;
use App\Models\CompanyProfile;
use App\Models\DailySale;
use App\Models\Lifting;
use App\Models\Product;
use App\Models\SalesRep;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $month = Carbon::parse($selectedDate)->startOfMonth();

        $company = CompanyProfile::first();

        $todaySales = DailySale::where('sale_date', $selectedDate)->sum('total_taka');
        $todayDiscount = DailySale::where('sale_date', $selectedDate)->sum('discount');
        $todayNet = DailySale::where('sale_date', $selectedDate)->sum('net_amount');
        $todayPurchaseValue = Lifting::where('lifting_date', $selectedDate)->sum('value');
        $todayPayment = CashCreditEntry::where('entry_date', $selectedDate)
            ->selectRaw('SUM(collection + credit_collection) as total')->value('total') ?? 0;

        $stockValue = $this->totalStockValue($selectedDate);
        $todayCost = DailySale::where('sale_date', $selectedDate)
            ->join('products', 'products.id', '=', 'daily_sales.product_id')
            ->selectRaw('SUM(daily_sales.sales_pcs * products.cost_price) as total')
            ->value('total') ?? 0;
        $todayProfit = $todayNet - $todayCost;

        $monthlyDaily = DailySale::whereBetween('sale_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->selectRaw('sale_date, SUM(net_amount) as total_net')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->sale_date)->format('j'));

        $daysInMonth = $month->copy()->endOfMonth()->day;
        $dailyChart = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dailyChart[] = (float) ($monthlyDaily[$d]->total_net ?? 0);
        }

        $workingDays = Carbon::today()->day;
        $daysRemaining = max(0, $daysInMonth - $workingDays);

        $monthNet = array_sum($dailyChart);
        $productCount = Product::where('is_active', true)->count();
        $salesRepCount = SalesRep::where('is_active', true)->count();
        $lowStockCount = $this->lowStockCount($selectedDate);

        $repPerformance = SalesRep::where('is_active', true)->get()->map(function (SalesRep $rep) use ($selectedDate) {
            $net = DailySale::where('sale_date', $selectedDate)->where('sales_rep_id', $rep->id)->sum('net_amount');

            return [
                'rep' => $rep,
                'net' => $net,
                'target' => $rep->sales_target,
                'achievement' => $rep->sales_target > 0 ? round(($net / $rep->sales_target) * 100, 1) : null,
            ];
        });

        return view('dashboard.index', [
            'company' => $company,
            'selectedDate' => $selectedDate,
            'todaySales' => $todaySales,
            'todayDiscount' => $todayDiscount,
            'todayNet' => $todayNet,
            'todayPurchaseValue' => $todayPurchaseValue,
            'todayPayment' => $todayPayment,
            'todayProfit' => $todayProfit,
            'stockValue' => $stockValue,
            'supplierDue' => $company?->supplier_due ?? 0,
            'productCount' => $productCount,
            'salesRepCount' => $salesRepCount,
            'lowStockCount' => $lowStockCount,
            'daysInMonth' => $daysInMonth,
            'workingDays' => $workingDays,
            'daysRemaining' => $daysRemaining,
            'monthNet' => $monthNet,
            'dailyChart' => $dailyChart,
            'repPerformance' => $repPerformance,
        ]);
    }

    private function totalStockValue(string $asOf): float
    {
        $liftedTotals = Lifting::where('lifting_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(total_lifted_pcs) as pcs')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $soldTotals = DailySale::where('sale_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(sales_pcs) as pcs')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $total = 0;
        foreach (Product::all() as $product) {
            $opening = ($product->opening_stock_ctn * $product->pic) + $product->opening_stock_pcs;
            $lifted = $liftedTotals->get($product->id)?->pcs ?? 0;
            $sold = $soldTotals->get($product->id)?->pcs ?? 0;
            $closing = $opening + $lifted - $sold;
            $total += $closing * $product->price;
        }

        return $total;
    }

    private function lowStockCount(string $asOf): int
    {
        $liftedTotals = Lifting::where('lifting_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(total_lifted_pcs) as pcs')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $soldTotals = DailySale::where('sale_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(sales_pcs) as pcs')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $count = 0;
        foreach (Product::where('is_active', true)->get() as $product) {
            $opening = ($product->opening_stock_ctn * $product->pic) + $product->opening_stock_pcs;
            $lifted = $liftedTotals->get($product->id)?->pcs ?? 0;
            $sold = $soldTotals->get($product->id)?->pcs ?? 0;
            $closingCtn = $product->pic > 0 ? ($opening + $lifted - $sold) / $product->pic : 0;
            if ($closingCtn < $product->low_stock_threshold_ctn) {
                $count++;
            }
        }

        return $count;
    }
}
