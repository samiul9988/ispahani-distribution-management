<?php

namespace App\Http\Controllers;

use App\Models\Lifting;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LiftingController extends Controller
{
    public function index(Request $request): View
    {
        $summaries = Lifting::query()
            ->select('lifting_date')
            ->selectRaw('SUM(total_lifted_pcs) as total_pcs, SUM(value) as total_value')
            ->groupBy('lifting_date')
            ->orderByDesc('lifting_date')
            ->paginate(20);

        return view('liftings.index', compact('summaries'));
    }

    public function entry(Request $request): View
    {
        $data = $request->validate([
            'lifting_date' => ['nullable', 'date'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ]);

        $liftingDate = $data['lifting_date'] ?? now()->format('Y-m-d');
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $supplierId = $data['supplier_id'] ?? $suppliers->first()?->id;

        $products = Product::where('is_active', true)->orderBy('name')->get();

        $existing = Lifting::where('lifting_date', $liftingDate)->get()->keyBy('product_id');

        return view('liftings.entry', [
            'liftingDate' => $liftingDate,
            'products' => $products,
            'existing' => $existing,
            'suppliers' => $suppliers,
            'supplierId' => $supplierId,
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lifting_date' => ['required', 'date'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'rows' => ['required', 'array'],
            'rows.*.lifting_ctn' => ['nullable', 'numeric', 'min:0'],
            'rows.*.lifting_pcs' => ['nullable', 'numeric', 'min:0'],
            'rows.*.value' => ['nullable', 'numeric', 'min:0'],
        ]);

        $liftingDate = $validated['lifting_date'];
        $supplierId = $validated['supplier_id'];

        DB::transaction(function () use ($validated, $liftingDate, $supplierId) {
            foreach ($validated['rows'] as $productId => $row) {
                $product = Product::find($productId);
                if (! $product) {
                    continue;
                }

                $liftingCtn = (float) ($row['lifting_ctn'] ?? 0);
                $liftingPcs = (float) ($row['lifting_pcs'] ?? 0);
                $value = (float) ($row['value'] ?? 0);

                $totalLiftedPcs = ($liftingCtn * $product->pic) + $liftingPcs;

                if ($totalLiftedPcs == 0 && $value == 0) {
                    Lifting::where('lifting_date', $liftingDate)
                        ->where('product_id', $productId)
                        ->delete();

                    continue;
                }

                Lifting::updateOrCreate(
                    [
                        'lifting_date' => $liftingDate,
                        'product_id' => $productId,
                    ],
                    [
                        'supplier_id' => $supplierId,
                        'lifting_ctn' => $liftingCtn,
                        'lifting_pcs' => $liftingPcs,
                        'total_lifted_pcs' => $totalLiftedPcs,
                        'value' => $value,
                    ]
                );
            }
        });

        return redirect()
            ->route('liftings.entry', ['lifting_date' => $liftingDate])
            ->with('success', 'Lifting entry saved for '.$liftingDate.'.');
    }
}
