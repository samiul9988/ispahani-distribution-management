<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Retailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RetailerController extends Controller
{
    public function index(): View
    {
        $retailers = Retailer::with('market')->orderBy('name')->paginate(20);

        return view('retailers.index', compact('retailers'));
    }

    public function create(): View
    {
        $markets = Market::where('is_active', true)->orderBy('name')->get();

        return view('retailers.create', compact('markets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Retailer::create($data);

        return redirect()->route('retailers.index')->with('success', 'Customer added.');
    }

    public function edit(Retailer $retailer): View
    {
        $markets = Market::where('is_active', true)->orderBy('name')->get();

        return view('retailers.edit', compact('retailer', 'markets'));
    }

    public function update(Request $request, Retailer $retailer): RedirectResponse
    {
        $data = $this->validated($request);
        $retailer->update($data);

        return redirect()->route('retailers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Retailer $retailer): RedirectResponse
    {
        $retailer->delete();

        return redirect()->route('retailers.index')->with('success', 'Customer deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shop_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'area' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'market_id' => ['nullable', 'exists:markets,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
