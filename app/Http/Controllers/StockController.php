<?php

namespace App\Http\Controllers;

use App\Models\DamagedStock;
use App\Models\Lifting;
use App\Models\DailySale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $asOf = $request->get('as_of', now()->format('Y-m-d'));

        $liftedTotals = Lifting::where('lifting_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(total_lifted_pcs) as pcs, SUM(value) as value')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $soldTotals = DailySale::where('sale_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(sales_pcs) as pcs')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $damagedTotals = DamagedStock::where('damage_date', '<=', $asOf)
            ->selectRaw('product_id, SUM(qty_pcs) as pcs')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $products = Product::orderBy('name')->get();

        $rows = $products->map(function (Product $product) use ($liftedTotals, $soldTotals, $damagedTotals) {
            $openingPcs = ($product->opening_stock_ctn * $product->pic) + $product->opening_stock_pcs;
            $liftedPcs = $liftedTotals->get($product->id)?->pcs ?? 0;
            $liftedValue = $liftedTotals->get($product->id)?->value ?? 0;
            $soldPcs = $soldTotals->get($product->id)?->pcs ?? 0;
            $damagedPcs = $damagedTotals->get($product->id)?->pcs ?? 0;

            $closingPcs = $openingPcs + $liftedPcs - $soldPcs - $damagedPcs;
            $closingCtn = $product->pic > 0 ? $closingPcs / $product->pic : 0;
            $stockValue = $closingPcs * $product->price;
            $isLow = $closingCtn < $product->low_stock_threshold_ctn;

            return [
                'product' => $product,
                'opening_pcs' => $openingPcs,
                'lifted_pcs' => $liftedPcs,
                'lifted_value' => $liftedValue,
                'sold_pcs' => $soldPcs,
                'damaged_pcs' => $damagedPcs,
                'closing_pcs' => $closingPcs,
                'closing_ctn' => $closingCtn,
                'stock_value' => $stockValue,
                'is_low' => $isLow,
            ];
        });

        $totals = [
            'lifted_value' => $rows->sum('lifted_value'),
            'stock_value' => $rows->sum('stock_value'),
            'low_count' => $rows->where('is_low', true)->count(),
        ];

        return view('stock.index', compact('rows', 'asOf', 'totals'));
    }
}
