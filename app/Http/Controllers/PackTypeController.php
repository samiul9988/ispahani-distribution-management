<?php

namespace App\Http\Controllers;

use App\Models\PackType;
use App\Models\QuantityType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackTypeController extends Controller
{
    public function index(): View
    {
        $packTypes = PackType::orderBy('value')->get();
        $quantityTypes = QuantityType::orderBy('value')->get();

        return view('pack_types.index', compact('packTypes', 'quantityTypes'));
    }

    public function storePack(Request $request): RedirectResponse
    {
        $data = $request->validate(['value' => ['required', 'string', 'max:100', 'unique:pack_types,value']]);
        PackType::create(['value' => $data['value'], 'source' => 'custom']);

        return redirect()->route('pack-types.index')->with('success', 'Pack type added.');
    }

    public function storeQuantity(Request $request): RedirectResponse
    {
        $data = $request->validate(['value' => ['required', 'string', 'max:100', 'unique:quantity_types,value']]);
        QuantityType::create(['value' => $data['value'], 'source' => 'custom']);

        return redirect()->route('pack-types.index')->with('success', 'Quantity type added.');
    }

    public function destroyPack(PackType $packType): RedirectResponse
    {
        if ($packType->source === 'system') {
            return back()->with('error', 'System defaults cannot be removed.');
        }
        $packType->delete();

        return redirect()->route('pack-types.index')->with('success', 'Pack type removed.');
    }

    public function destroyQuantity(QuantityType $quantityType): RedirectResponse
    {
        if ($quantityType->source === 'system') {
            return back()->with('error', 'System defaults cannot be removed.');
        }
        $quantityType->delete();

        return redirect()->route('pack-types.index')->with('success', 'Quantity type removed.');
    }
}
