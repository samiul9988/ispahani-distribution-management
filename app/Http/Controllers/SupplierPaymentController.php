<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Lifting;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierPaymentController extends Controller
{
    public function index(): View
    {
        $company = CompanyProfile::first();
        $openingDue = $company?->supplier_due ?? 0;

        $totalLifted = Lifting::sum('value');
        $totalPaid = SupplierPayment::sum('amount');
        $currentDue = $openingDue + $totalLifted - $totalPaid;

        $supplierBreakdown = Supplier::withSum('liftings', 'value')
            ->withSum('payments', 'amount')
            ->get()
            ->map(fn (Supplier $s) => [
                'supplier' => $s,
                'lifted' => $s->liftings_sum_value ?? 0,
                'paid' => $s->payments_sum_amount ?? 0,
                'due' => ($s->opening_balance) + ($s->liftings_sum_value ?? 0) - ($s->payments_sum_amount ?? 0),
            ]);

        $payments = SupplierPayment::with('supplier')->orderByDesc('payment_date')->paginate(20);

        return view('supplier_payments.index', compact(
            'payments', 'openingDue', 'totalLifted', 'totalPaid', 'currentDue', 'supplierBreakdown'
        ));
    }

    public function create(): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('supplier_payments.create', compact('suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        SupplierPayment::create($data);

        return redirect()->route('supplier-payments.index')->with('success', 'Supplier payment recorded.');
    }

    public function destroy(SupplierPayment $supplierPayment): RedirectResponse
    {
        $supplierPayment->delete();

        return redirect()->route('supplier-payments.index')->with('success', 'Payment record deleted.');
    }
}
