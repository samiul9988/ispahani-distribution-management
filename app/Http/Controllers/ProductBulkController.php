<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductBulkController extends Controller
{
    public function multiCreate(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.multi_create', compact('categories'));
    }

    public function multiStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.category_id' => ['nullable', 'exists:categories,id'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
            'products.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'products.*.pic' => ['required', 'integer', 'min:1'],
            'products.*.opening_stock_ctn' => ['nullable', 'numeric', 'min:0'],
            'products.*.low_stock_threshold_ctn' => ['nullable', 'integer', 'min:0'],
        ]);

        $count = 0;
        foreach ($validated['products'] as $row) {
            if (empty($row['name'])) {
                continue;
            }

            Product::create([
                'name' => $row['name'],
                'category_id' => $row['category_id'] ?? null,
                'price' => $row['price'],
                'cost_price' => $row['cost_price'] ?? 0,
                'pic' => $row['pic'],
                'opening_stock_ctn' => $row['opening_stock_ctn'] ?? 0,
                'opening_stock_pcs' => 0,
                'low_stock_threshold_ctn' => $row['low_stock_threshold_ctn'] ?? 5,
                'is_active' => true,
            ]);
            $count++;
        }

        return redirect()->route('products.index')->with('success', "{$count} products added.");
    }

    public function lowStock(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        $rows = $products->map(function (Product $product) {
            $closingCtn = $this->closingCtn($product);

            return [
                'product' => $product,
                'closing_ctn' => $closingCtn,
                'threshold' => $product->low_stock_threshold_ctn,
                'ratio' => $product->low_stock_threshold_ctn > 0 ? $closingCtn / $product->low_stock_threshold_ctn : 1,
            ];
        })->filter(fn ($r) => $r['closing_ctn'] < $r['threshold'])->sortBy('ratio')->values();

        $outOfStock = $rows->where('closing_ctn', '<=', 0)->count();
        $critical = $rows->where('ratio', '>', 0)->where('ratio', '<', 0.3)->count();
        $warning = $rows->where('ratio', '>=', 0.3)->where('ratio', '<', 1)->count();
        $reorderValue = $rows->sum(fn ($r) => max(0, $r['threshold'] - $r['closing_ctn']) * $r['product']->pic * $r['product']->cost_price);

        return view('products.low_stock', [
            'rows' => $rows,
            'totalLow' => $rows->count(),
            'outOfStock' => $outOfStock,
            'critical' => $critical,
            'warning' => $warning,
            'reorderValue' => $reorderValue,
        ]);
    }

    public function bulkEdit(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('products.bulk_edit', compact('products'));
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'products' => ['required', 'array'],
            'products.*.price' => ['nullable', 'numeric', 'min:0'],
            'products.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'products.*.low_stock_threshold_ctn' => ['nullable', 'integer', 'min:0'],
        ]);

        $updated = 0;
        foreach ($validated['products'] as $productId => $row) {
            $product = Product::find($productId);
            if (! $product) {
                continue;
            }

            $product->update([
                'price' => $row['price'] ?? $product->price,
                'cost_price' => $row['cost_price'] ?? $product->cost_price,
                'low_stock_threshold_ctn' => $row['low_stock_threshold_ctn'] ?? $product->low_stock_threshold_ctn,
            ]);
            $updated++;
        }

        return redirect()->route('products.bulk-edit')->with('success', "{$updated} products updated.");
    }

    private function closingCtn(Product $product): float
    {
        $opening = ($product->opening_stock_ctn * $product->pic) + $product->opening_stock_pcs;
        $lifted = $product->liftings()->sum('total_lifted_pcs');
        $sold = $product->dailySales()->sum('sales_pcs');
        $damaged = \App\Models\DamagedStock::where('product_id', $product->id)->sum('qty_pcs');

        $closingPcs = $opening + $lifted - $sold - $damaged;

        return $product->pic > 0 ? $closingPcs / $product->pic : 0;
    }
}
