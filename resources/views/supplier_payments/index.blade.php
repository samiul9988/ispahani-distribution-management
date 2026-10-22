@extends('layouts.app')

@section('title', 'Supplier Payments (Ispahani)')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Opening Due</div>
            <div class="fs-5 fw-bold">{{ number_format($openingDue, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total Lifted (Purchases)</div>
            <div class="fs-5 fw-bold">{{ number_format($totalLifted, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total Paid</div>
            <div class="fs-5 fw-bold text-success">{{ number_format($totalPaid, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Current Due to Ispahani</div>
            <div class="fs-5 fw-bold text-danger">{{ number_format($currentDue, 2) }}</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('supplier-payments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Record Payment</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Date</th><th>Method</th><th>Note</th><th class="text-end">Amount</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
                @forelse ($payments as $p)
                    <tr>
                        <td>{{ $p->payment_date->format('d M Y') }}</td>
                        <td>{{ $p->method ?? '—' }}</td>
                        <td>{{ $p->note ?? '—' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($p->amount, 2) }}</td>
                        <td class="text-end">
                            <form action="{{ route('supplier-payments.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this payment record?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $payments->links() }}</div>
@endsection
