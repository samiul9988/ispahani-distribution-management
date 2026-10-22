<?php

namespace App\Http\Controllers;

use App\Models\Lifting;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        $orders = PurchaseOrder::with('supplier')->orderByDesc('po_date')->paginate(20);

        return view('purchase_orders.index', compact('orders'));
    }

    public function create(): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'po_date' => ['required', 'date'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
            'action' => ['required', 'in:draft,receive'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty_ctn' => ['nullable', 'numeric', 'min:0'],
            'items.*.qty_pcs' => ['nullable', 'numeric', 'min:0'],
            'items.*.buy_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $itemRows = [];
            $totalQty = 0;
            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if (! $product) {
                    continue;
                }

                $qtyCtn = (float) ($item['qty_ctn'] ?? 0);
                $qtyPcs = (float) ($item['qty_pcs'] ?? 0);
                $totalPcs = ($qtyCtn * $product->pic) + $qtyPcs;

                if ($totalPcs <= 0) {
                    continue;
                }

                $buyPrice = (float) ($item['buy_price'] ?? $product->cost_price);
                $totalCost = $totalPcs * $buyPrice;

                $itemRows[] = [
                    'product_id' => $product->id,
                    'qty_ctn' => $qtyCtn,
                    'qty_pcs' => $qtyPcs,
                    'total_pcs' => $totalPcs,
                    'buy_price' => $buyPrice,
                    'total_cost' => $totalCost,
                ];

                $totalQty += $totalPcs;
                $totalAmount += $totalCost;
            }

            $status = $validated['action'] === 'receive' ? 'received' : 'draft';

            $order = PurchaseOrder::create([
                'po_date' => $validated['po_date'],
                'invoice_number' => $validated['invoice_number'] ?? null,
                'supplier_id' => $validated['supplier_id'],
                'payment_method' => $validated['payment_method'] ?? null,
                'total_qty_pcs' => $totalQty,
                'total_amount' => $totalAmount,
                'payment_amount' => (float) ($validated['payment_amount'] ?? 0),
                'status' => $status,
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($itemRows as $row) {
                $order->items()->create($row);
            }

            if ($status === 'received') {
                $this->bookIntoLifting($order, $itemRows);
            }

            return $order;
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order '.($order->status === 'received' ? 'received and stock updated.' : 'saved as draft.'));
    }

    public function receive(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'Already received.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $items = $purchaseOrder->items->map(fn ($i) => [
                'product_id' => $i->product_id,
                'qty_ctn' => $i->qty_ctn,
                'qty_pcs' => $i->qty_pcs,
                'total_pcs' => $i->total_pcs,
                'total_cost' => $i->total_cost,
            ])->all();

            $this->bookIntoLifting($purchaseOrder, $items);
            $purchaseOrder->update(['status' => 'received']);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order received and stock updated.');
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function bookIntoLifting(PurchaseOrder $order, array $items): void
    {
        foreach ($items as $item) {
            $lifting = Lifting::firstOrNew([
                'lifting_date' => $order->po_date->format('Y-m-d'),
                'product_id' => $item['product_id'],
            ]);

            $lifting->supplier_id = $order->supplier_id;
            $lifting->lifting_ctn = ($lifting->lifting_ctn ?? 0) + $item['qty_ctn'];
            $lifting->lifting_pcs = ($lifting->lifting_pcs ?? 0) + $item['qty_pcs'];
            $lifting->total_lifted_pcs = ($lifting->total_lifted_pcs ?? 0) + $item['total_pcs'];
            $lifting->value = ($lifting->value ?? 0) + $item['total_cost'];
            $lifting->save();
        }
    }
}
