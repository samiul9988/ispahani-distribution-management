<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketController extends Controller
{
    public function index(): View
    {
        $markets = Market::withCount('salesReps')->orderBy('name')->paginate(20);

        return view('markets.index', compact('markets'));
    }

    public function create(): View
    {
        return view('markets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:markets,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        Market::create($data);

        return redirect()->route('markets.index')->with('success', 'Market created.');
    }

    public function edit(Market $market): View
    {
        return view('markets.edit', compact('market'));
    }

    public function update(Request $request, Market $market): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:markets,name,'.$market->id],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $market->update($data);

        return redirect()->route('markets.index')->with('success', 'Market updated.');
    }

    public function destroy(Market $market): RedirectResponse
    {
        $market->delete();

        return redirect()->route('markets.index')->with('success', 'Market deleted.');
    }
}
