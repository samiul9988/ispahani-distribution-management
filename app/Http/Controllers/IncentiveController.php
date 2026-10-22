<?php

namespace App\Http\Controllers;

use App\Models\Incentive;
use App\Models\SalesRep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncentiveController extends Controller
{
    public function index(): View
    {
        $incentives = Incentive::with('salesRep')->orderByDesc('incentive_date')->paginate(20);
        $total = Incentive::sum('amount');

        return view('incentives.index', compact('incentives', 'total'));
    }

    public function create(): View
    {
        $salesReps = SalesRep::where('is_active', true)->orderBy('name')->get();

        return view('incentives.create', compact('salesReps'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'incentive_date' => ['required', 'date'],
            'sales_rep_id' => ['required', 'exists:sales_reps,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        Incentive::create($data);

        return redirect()->route('incentives.index')->with('success', 'Incentive recorded.');
    }

    public function destroy(Incentive $incentive): RedirectResponse
    {
        $incentive->delete();

        return redirect()->route('incentives.index')->with('success', 'Incentive deleted.');
    }
}
