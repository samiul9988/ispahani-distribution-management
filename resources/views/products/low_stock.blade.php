@extends('layouts.app')

@section('title', 'Low Stock Alert')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Total Low Stock</div>
            <div class="fs-4 fw-bold">{{ $totalLow }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Out of Stock</div>
            <div class="fs-4 fw-bold text-danger">{{ $outOfStock }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Critical (&lt;30%)</div>
            <div class="fs-4 fw-bold text-danger">{{ $critical }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Warning (30-100%)</div>
            <div class="fs-4 fw-bold text-warning">{{ $warning }}</div>
        </div>
    </div>
</div>

<div class="card card-kpi p-3 mb-4">
    <div class="text-muted small">Estimated Reorder Cost (to reach threshold)</div>
    <div class="fs-4 fw-bold">৳{{ number_format($reorderValue, 2) }}</div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Product</th><th class="text-end">Current Stock (ctn)</th><th class="text-end">Threshold (ctn)</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row['product']->name }}</td>
                        <td class="text-end">{{ number_format($row['closing_ctn'], 2) }}</td>
                        <td class="text-end">{{ $row['threshold'] }}</td>
                        <td>
                            @if ($row['closing_ctn'] <= 0)
                                <span class="badge bg-danger">Out of Stock</span>
                            @elseif ($row['ratio'] < 0.3)
                                <span class="badge bg-danger">Critical</span>
                            @else
                                <span class="badge bg-warning text-dark">Warning</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">All stock levels are healthy!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
