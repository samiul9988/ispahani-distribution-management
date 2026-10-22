@extends('layouts.app')

@section('title', 'Damaged Stock')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total Damaged Value</div>
            <div class="fs-5 fw-bold text-danger">{{ number_format($totalValue, 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Pending (not sent to supplier)</div>
            <div class="fs-5 fw-bold text-warning">{{ number_format($pendingValue, 2) }}</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('damaged-stock.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Record Damage</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Date</th><th>Product</th><th class="text-end">Qty (pcs)</th><th class="text-end">Value</th><th>Reason</th><th>Status</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
                @forelse ($damages as $d)
                    <tr>
                        <td>{{ $d->damage_date->format('d M Y') }}</td>
                        <td>{{ $d->product?->name }}</td>
                        <td class="text-end">{{ number_format($d->qty_pcs, 2) }}</td>
                        <td class="text-end">{{ number_format($d->value, 2) }}</td>
                        <td>{{ $d->reason ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $d->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">{{ $d->status }}</span>
                        </td>
                        <td class="text-end">
                            @if ($d->status === 'pending')
                                <form action="{{ route('damaged-stock.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No damaged stock recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $damages->links() }}</div>
@endsection
