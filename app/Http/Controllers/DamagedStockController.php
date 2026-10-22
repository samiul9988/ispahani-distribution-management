<?php

namespace App\Http\Controllers;

use App\Models\DamagedStock;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DamagedStockController extends Controller
{
    public function index(): View
    {
        $damages = DamagedStock::with('product')->orderByDesc('damage_date')->paginate(20);
        $totalValue = DamagedStock::sum('value');
        $pendingValue = DamagedStock::where('status', 'pending')->sum('value');

        return view('damaged_stock.index', compact('damages', 'totalValue', 'pendingValue'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('damaged_stock.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'damage_date' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'qty_pcs' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        DamagedStock::create([
            ...$data,
            'value' => $data['qty_pcs'] * $product->price,
            'status' => 'pending',
        ]);

        return redirect()->route('damaged-stock.index')->with('success', 'Damaged stock recorded.');
    }

    public function destroy(DamagedStock $damagedStock): RedirectResponse
    {
        $damagedStock->delete();

        return redirect()->route('damaged-stock.index')->with('success', 'Damaged stock record deleted.');
    }
}
