@extends('layouts.app')

@section('title', 'Transactions')

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
    </form>
</div>

<div class="row g-3 mb-4">
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
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Net</div>
            <div class="fs-4 fw-bold">৳{{ number_format($totalIn - $totalOut, 2) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Date</th><th>Type</th><th>Description</th><th class="text-end">In</th><th class="text-end">Out</th></tr>
            </thead>
            <tbody>
                @forelse ($rows as $r)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($r['date'])->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ $r['type'] }}</span></td>
                        <td>{{ $r['description'] }}</td>
                        <td class="text-end text-success">{{ $r['direction'] === 'in' ? number_format($r['amount'], 2) : '' }}</td>
                        <td class="text-end text-danger">{{ $r['direction'] === 'out' ? number_format($r['amount'], 2) : '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No transactions in this range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
