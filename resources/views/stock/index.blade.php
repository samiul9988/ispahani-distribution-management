@extends('layouts.app')

@section('title', 'Stock Ledger')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">As of Date</label>
            <input type="date" name="as_of" class="form-control" value="{{ $asOf }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel"></i> Apply</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total Lifted Value (as of {{ $asOf }})</div>
            <div class="fs-4 fw-bold text-primary">{{ number_format($totals['lifted_value'], 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total Stock Value</div>
            <div class="fs-4 fw-bold text-success">{{ number_format($totals['stock_value'], 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Low Stock Products</div>
            <div class="fs-4 fw-bold {{ $totals['low_count'] > 0 ? 'text-danger' : 'text-muted' }}">{{ $totals['low_count'] }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm mb-0 align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-end">Opening (pcs)</th>
                    <th class="text-end">Lifted (pcs)</th>
                    <th class="text-end">Sold (pcs)</th>
                    <th class="text-end">Damaged (pcs)</th>
                    <th class="text-end">Closing (pcs)</th>
                    <th class="text-end">Closing (ctn)</th>
                    <th class="text-end">Stock Value</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr class="{{ $row['is_low'] ? 'table-danger' : '' }}">
                        <td>{{ $row['product']->name }}</td>
                        <td class="text-end">{{ number_format($row['opening_pcs'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['lifted_pcs'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['sold_pcs'], 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($row['damaged_pcs'], 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($row['closing_pcs'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['closing_ctn'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['stock_value'], 2) }}</td>
                        <td class="text-center">
                            @if ($row['is_low'])
                                <span class="badge bg-danger">Low Stock</span>
                            @else
                                <span class="badge bg-success">OK</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
