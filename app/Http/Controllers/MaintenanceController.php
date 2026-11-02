<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function show(Request $request): View
    {
        return view('dashboard.activation', [
            'active' => Setting::get('maintenance', 'n') === 'y',
            'key' => $request->query('key'),
        ]);
    }

    public function toggle(Request $request): RedirectResponse
    {
        $turningOn = Setting::get('maintenance', 'n') !== 'y';

        Setting::set('maintenance', $turningOn ? 'y' : 'n');

        $status = $turningOn
            ? 'Maintenance mode is now on — visitors will see the maintenance page.'
            : 'Maintenance mode is now off — the site is live for everyone.';

        return redirect()->route('maintenance.show', $request->filled('key') ? ['key' => $request->input('key')] : [])
            ->with('status', $status);
    }

    public function preview(): View
    {
        return view('errors.503');
    }
}
