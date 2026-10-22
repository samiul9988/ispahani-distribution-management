<?php

namespace App\Http\Controllers;

use App\Models\FreeOffer;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FreeOfferController extends Controller
{
    public function index(): View
    {
        $offers = FreeOffer::with('product')->orderByDesc('id')->paginate(20);

        return view('free_offers.index', compact('offers'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('free_offers.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        FreeOffer::create($data);

        return redirect()->route('free-offers.index')->with('success', 'Offer created.');
    }

    public function edit(FreeOffer $freeOffer): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('free_offers.edit', compact('freeOffer', 'products'));
    }

    public function update(Request $request, FreeOffer $freeOffer): RedirectResponse
    {
        $data = $this->validated($request);

        $freeOffer->update($data);

        return redirect()->route('free-offers.index')->with('success', 'Offer updated.');
    }

    public function destroy(FreeOffer $freeOffer): RedirectResponse
    {
        $freeOffer->delete();

        return redirect()->route('free-offers.index')->with('success', 'Offer deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:free_qty,fixed_discount'],
            'buy_qty' => ['required', 'numeric', 'min:0.01'],
            'free_qty' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
