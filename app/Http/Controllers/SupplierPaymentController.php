<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Lifting;
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

        $payments = SupplierPayment::orderByDesc('payment_date')->paginate(20);

        return view('supplier_payments.index', compact(
            'payments', 'openingDue', 'totalLifted', 'totalPaid', 'currentDue'
        ));
    }

    public function create(): View
    {
        return view('supplier_payments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
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
