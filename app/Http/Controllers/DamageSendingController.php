<?php

namespace App\Http\Controllers;

use App\Models\DamagedStock;
use App\Models\DamageSending;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DamageSendingController extends Controller
{
    public function index(): View
    {
        $sendings = DamageSending::with('supplier')->orderByDesc('sending_date')->paginate(20);
        $pendingDamages = DamagedStock::with('product')->where('status', 'pending')->orderByDesc('damage_date')->get();

        return view('damage_sendings.index', compact('sendings', 'pendingDamages'));
    }

    public function create(): View
    {
        $pendingDamages = DamagedStock::with('product')->where('status', 'pending')->orderByDesc('damage_date')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('damage_sendings.create', compact('pendingDamages', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sending_date' => ['required', 'date'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'note' => ['nullable', 'string', 'max:255'],
            'damaged_stock_ids' => ['required', 'array', 'min:1'],
            'damaged_stock_ids.*' => ['exists:damaged_stocks,id'],
        ]);

        DB::transaction(function () use ($data) {
            $items = DamagedStock::whereIn('id', $data['damaged_stock_ids'])->where('status', 'pending')->get();

            $sending = DamageSending::create([
                'sending_date' => $data['sending_date'],
                'supplier_id' => $data['supplier_id'],
                'total_qty_pcs' => $items->sum('qty_pcs'),
                'total_value' => $items->sum('value'),
                'status' => 'sent',
                'note' => $data['note'] ?? null,
            ]);

            DamagedStock::whereIn('id', $items->pluck('id'))->update([
                'damage_sending_id' => $sending->id,
                'status' => 'sent_to_supplier',
            ]);
        });

        return redirect()->route('damage-sendings.index')->with('success', 'Damaged stock sent to supplier.');
    }
}
