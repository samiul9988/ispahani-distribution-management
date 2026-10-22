<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::orderByDesc('payment_date')->paginate(20);
        $totalIn = Payment::where('direction', 'in')->sum('amount');
        $totalOut = Payment::where('direction', 'out')->sum('amount');

        return view('payments.index', compact('payments', 'totalIn', 'totalOut'));
    }

    public function create(): View
    {
        return view('payments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'payment_date' => ['required', 'date'],
            'direction' => ['required', 'in:in,out'],
            'party_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        Payment::create($data);

        return redirect()->route('payments.index')->with('success', 'Payment recorded.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted.');
    }
}
