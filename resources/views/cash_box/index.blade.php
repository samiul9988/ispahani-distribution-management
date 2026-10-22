@extends('layouts.app')

@section('title', 'Cash Box')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('cash-box.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="cash_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-cash-stack"></i> Open / Close</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th><th class="text-end">Opening</th><th class="text-end">Collection</th>
                    <th class="text-end">Credit Collection</th><th class="text-end">Expense</th>
                    <th class="text-end">Supplier Payment</th><th class="text-end">Adjustment</th>
                    <th class="text-end">Closing</th><th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($boxes as $b)
                    <tr>
                        <td>{{ $b->cash_date->format('d M Y') }}</td>
                        <td class="text-end">{{ number_format($b->opening_balance, 2) }}</td>
                        <td class="text-end">{{ number_format($b->total_collection, 2) }}</td>
                        <td class="text-end">{{ number_format($b->total_credit_collection, 2) }}</td>
                        <td class="text-end">{{ number_format($b->total_expense, 2) }}</td>
                        <td class="text-end">{{ number_format($b->total_supplier_payment, 2) }}</td>
                        <td class="text-end">{{ number_format($b->adjustment, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($b->closing_balance, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('cash-box.entry', ['cash_date' => $b->cash_date->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No cash box entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $boxes->links() }}</div>
@endsection
