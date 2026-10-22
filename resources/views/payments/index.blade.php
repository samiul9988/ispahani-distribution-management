@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total In</div>
            <div class="fs-4 fw-bold text-success">৳{{ number_format($totalIn, 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total Out</div>
            <div class="fs-4 fw-bold text-danger">৳{{ number_format($totalOut, 2) }}</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('payments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Record Payment</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Date</th><th>Direction</th><th>Party</th><th>Method</th><th>Note</th><th class="text-end">Amount</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                @forelse ($payments as $p)
                    <tr>
                        <td>{{ $p->payment_date->format('d M Y') }}</td>
                        <td><span class="badge {{ $p->direction === 'in' ? 'bg-success' : 'bg-danger' }}">{{ strtoupper($p->direction) }}</span></td>
                        <td>{{ $p->party_name ?? '—' }}</td>
                        <td>{{ $p->method ?? '—' }}</td>
                        <td>{{ $p->note ?? '—' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($p->amount, 2) }}</td>
                        <td class="text-end">
                            <form action="{{ route('payments.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $payments->links() }}</div>
@endsection
