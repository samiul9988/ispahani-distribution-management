@extends('layouts.app')

@section('title', 'Damage Sendings')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('damage-sendings.create') }}" class="btn btn-primary"><i class="bi bi-send"></i> Send to Supplier</a>
</div>

<div class="card mb-4">
    <div class="card-header">Pending Damaged Stock (not yet sent)</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead><tr><th>Date</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Value</th></tr></thead>
            <tbody>
                @forelse ($pendingDamages as $d)
                    <tr>
                        <td>{{ $d->damage_date->format('d M Y') }}</td>
                        <td>{{ $d->product?->name }}</td>
                        <td class="text-end">{{ number_format($d->qty_pcs, 2) }}</td>
                        <td class="text-end">{{ number_format($d->value, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Nothing pending.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">Sending History</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Date</th><th>Supplier</th><th class="text-end">Qty (pcs)</th><th class="text-end">Value</th><th>Status</th><th>Note</th></tr></thead>
            <tbody>
                @forelse ($sendings as $s)
                    <tr>
                        <td>{{ $s->sending_date->format('d M Y') }}</td>
                        <td>{{ $s->supplier?->name }}</td>
                        <td class="text-end">{{ number_format($s->total_qty_pcs, 2) }}</td>
                        <td class="text-end">{{ number_format($s->total_value, 2) }}</td>
                        <td><span class="badge bg-info text-dark">{{ $s->status }}</span></td>
                        <td>{{ $s->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No sendings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $sendings->links() }}</div>
@endsection
