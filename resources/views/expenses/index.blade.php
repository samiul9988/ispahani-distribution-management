@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel"></i> Apply</button>
        </div>
        <div class="col-md-4 text-end">
            <span class="text-muted">Total: </span>
            <span class="fs-5 fw-bold text-danger">{{ number_format($total, 2) }}</span>
        </div>
    </form>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('expenses.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Expense</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Date</th><th>Category</th><th>Description</th><th class="text-end">Amount</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
                @forelse ($expenses as $e)
                    <tr>
                        <td>{{ $e->expense_date->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ $e->category }}</span></td>
                        <td>{{ $e->description ?? '—' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($e->amount, 2) }}</td>
                        <td class="text-end">
                            <form action="{{ route('expenses.destroy', $e) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No expenses in this range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $expenses->links() }}</div>
@endsection
