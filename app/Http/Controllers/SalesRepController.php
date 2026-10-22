<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\SalesRep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesRepController extends Controller
{
    public function index(): View
    {
        $salesReps = SalesRep::with('market')->orderBy('name')->paginate(20);

        return view('sales_reps.index', compact('salesReps'));
    }

    public function create(): View
    {
        $markets = Market::where('is_active', true)->orderBy('name')->get();

        return view('sales_reps.create', compact('markets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        SalesRep::create($data);

        return redirect()->route('sales-reps.index')->with('success', 'Sales rep created.');
    }

    public function edit(SalesRep $salesRep): View
    {
        $markets = Market::where('is_active', true)->orderBy('name')->get();

        return view('sales_reps.edit', compact('salesRep', 'markets'));
    }

    public function update(Request $request, SalesRep $salesRep): RedirectResponse
    {
        $data = $this->validated($request);

        $salesRep->update($data);

        return redirect()->route('sales-reps.index')->with('success', 'Sales rep updated.');
    }

    public function destroy(SalesRep $salesRep): RedirectResponse
    {
        $salesRep->delete();

        return redirect()->route('sales-reps.index')->with('success', 'Sales rep deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'market_id' => ['nullable', 'exists:markets,id'],
            'opening_credit' => ['nullable', 'numeric', 'min:0'],
            'sales_target' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['opening_credit'] = $data['opening_credit'] ?? 0;
        $data['sales_target'] = $data['sales_target'] ?? 0;

        return $data;
    }
}
