@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Selected Date</label>
            <input type="date" name="date" class="form-control" value="{{ $selectedDate }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel"></i> Apply</button>
        </div>
        <div class="col-md-7 text-end text-muted">
            {{ $company?->distributor_name }} &middot; {{ $company?->address }}
        </div>
    </form>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Today's Sales (Taka)</div>
            <div class="fs-4 fw-bold text-primary">{{ number_format($todaySales, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Today's Discount</div>
            <div class="fs-4 fw-bold text-warning">{{ number_format($todayDiscount, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Net Sales (after discount)</div>
            <div class="fs-4 fw-bold text-success">{{ number_format($todayNet, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Today's Profit</div>
            <div class="fs-4 fw-bold {{ $todayProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($todayProfit, 2) }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Purchase Value (Lifting)</div>
            <div class="fs-5 fw-semibold">{{ number_format($todayPurchaseValue, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Payment Collected</div>
            <div class="fs-5 fw-semibold">{{ number_format($todayPayment, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Supplier Due (Ispahani)</div>
            <div class="fs-5 fw-semibold text-danger">{{ number_format($supplierDue, 2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Stock Value (as of date)</div>
            <div class="fs-5 fw-semibold">{{ number_format($stockValue, 2) }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Today's Expense</div>
            <div class="fs-5 fw-semibold text-warning">{{ number_format($todayExpense, 2) }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Active Products</div>
            <div class="fs-5 fw-bold">{{ $productCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Active Sales Reps</div>
            <div class="fs-5 fw-bold">{{ $salesRepCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Low Stock Products</div>
            <div class="fs-5 fw-bold {{ $lowStockCount > 0 ? 'text-danger' : '' }}">{{ $lowStockCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Days Remaining This Month</div>
            <div class="fs-5 fw-bold">{{ $daysRemaining }} / {{ $daysInMonth }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card p-3">
            <h6 class="mb-3">Daily Net Sales — This Month (Total: {{ number_format($monthNet, 2) }})</h6>
            <canvas id="dailyChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>SR</th><th class="text-end">Net Sales</th><th class="text-end">Target %</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($repPerformance as $row)
                            <tr>
                                <td>{{ $row['rep']->name }}</td>
                                <td class="text-end">{{ number_format($row['net'], 2) }}</td>
                                <td class="text-end">
                                    @if ($row['achievement'] !== null)
                                        <span class="badge {{ $row['achievement'] >= 100 ? 'bg-success' : 'bg-secondary' }}">{{ $row['achievement'] }}%</span>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('dailyChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Array.from({length: {{ $daysInMonth }}}, (_, i) => i + 1),
            datasets: [{
                label: 'Net Sales (Taka)',
                data: @json($dailyChart),
                backgroundColor: '#3b82f6',
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } },
        },
    });
</script>
@endpush
