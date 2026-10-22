<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'offer_apply_mode' => Setting::get('offer_apply_mode', 'quantity'),
            'invoice_prefix' => Setting::get('invoice_prefix', 'PO-'),
            'low_stock_default_threshold' => Setting::get('low_stock_default_threshold', 5),
        ];

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'offer_apply_mode' => ['required', 'in:quantity,piece'],
            'invoice_prefix' => ['nullable', 'string', 'max:20'],
            'low_stock_default_threshold' => ['nullable', 'integer', 'min:0'],
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('settings.edit')->with('success', 'Settings saved.');
    }
}
