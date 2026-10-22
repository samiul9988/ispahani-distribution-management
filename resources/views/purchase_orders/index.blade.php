@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Purchase</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Date</th><th>Invoice No</th><th>Supplier</th><th class="text-end">Qty (pcs)</th><th class="text-end">Amount</th><th class="text-end">Paid</th><th>Status</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
                @forelse ($orders as $o)
                    <tr>
                        <td>{{ $o->po_date->format('d M Y') }}</td>
                        <td>{{ $o->invoice_number ?? '—' }}</td>
                        <td>{{ $o->supplier?->name }}</td>
                        <td class="text-end">{{ number_format($o->total_qty_pcs, 2) }}</td>
                        <td class="text-end">{{ number_format($o->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($o->payment_amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $o->status === 'received' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($o->status) }}</span>
                        </td>
                        <td class="text-end">
                            @if ($o->status === 'draft')
                                <form action="{{ route('purchase-orders.receive', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('Receive this order and update stock?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">Receive</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No purchases yet. Record goods received from a supplier to see them listed here.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
