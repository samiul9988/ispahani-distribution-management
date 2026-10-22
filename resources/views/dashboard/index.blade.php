@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card p-3 mb-4">
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

@php
    $kpi = function (string $icon, string $label, string $value, string $valueClass = '') {
        return compact('icon', 'label', 'value', 'valueClass');
    };
@endphp

@push('styles')
<style>
    .kpi-card { padding:1rem; }
    .kpi-card .kpi-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:.5rem; }
    .kpi-card .kpi-label { font-size:.8rem; color:#6b7280; font-weight:500; }
    .kpi-card .kpi-value { font-size:1.4rem; font-weight:700; color:#111827; }
    .section-label { font-size:.8rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.03em; margin:1.25rem 0 .6rem; display:flex; align-items:center; gap:.4rem; }
</style>
@endpush

<div class="section-label"><i class="bi bi-graph-up-arrow"></i> Sales Overview</div>
<div class="row g-3">
    @foreach ([
        $kpi('cash-stack', "Today's Sales", number_format($todaySales, 2)),
        $kpi('percent', "Today's Discount", number_format($todayDiscount, 2)),
        $kpi('check-circle', 'Net Sales (after discount)', number_format($todayNet, 2)),
        $kpi('graph-up', "Today's Profit", number_format($todayProfit, 2), $todayProfit >= 0 ? 'text-success' : 'text-danger'),
    ] as $c)
        <div class="col-6 col-md-3">
            <div class="card card-kpi kpi-card">
                <div class="kpi-top">
                    <div class="kpi-icon"><i class="bi bi-{{ $c['icon'] }}"></i></div>
                </div>
                <div class="kpi-label">{{ $c['label'] }}</div>
                <div class="kpi-value {{ $c['valueClass'] }}">৳{{ $c['value'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="section-label"><i class="bi bi-wallet2"></i> Finance Overview</div>
<div class="row g-3">
    @foreach ([
        $kpi('box-arrow-in-down', 'Purchase Value (Lifting)', number_format($todayPurchaseValue, 2)),
        $kpi('cash-coin', 'Payment Collected', number_format($todayPayment, 2)),
        $kpi('receipt', 'Supplier Due (Ispahani)', number_format($supplierDue, 2), 'text-danger'),
        $kpi('wallet2', "Today's Expense", number_format($todayExpense, 2), 'text-warning'),
    ] as $c)
        <div class="col-6 col-md-3">
            <div class="card card-kpi kpi-card">
                <div class="kpi-top">
                    <div class="kpi-icon"><i class="bi bi-{{ $c['icon'] }}"></i></div>
                </div>
                <div class="kpi-label">{{ $c['label'] }}</div>
                <div class="kpi-value {{ $c['valueClass'] }}">৳{{ $c['value'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="section-label"><i class="bi bi-boxes"></i> Inventory Overview</div>
<div class="row g-3">
    @foreach ([
        $kpi('archive', 'Stock Value (as of date)', number_format($stockValue, 2)),
        $kpi('exclamation-triangle', 'Low Stock Products', (string) $lowStockCount, $lowStockCount > 0 ? 'text-danger' : ''),
        $kpi('box-seam', 'Active Products', (string) $productCount),
        $kpi('person-badge', 'Active Sales Reps', (string) $salesRepCount),
    ] as $c)
        <div class="col-6 col-md-3">
            <div class="card card-kpi kpi-card">
                <div class="kpi-top">
                    <div class="kpi-icon"><i class="bi bi-{{ $c['icon'] }}"></i></div>
                </div>
                <div class="kpi-label">{{ $c['label'] }}</div>
                <div class="kpi-value {{ $c['valueClass'] }}">{{ $c['value'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mt-1">
    <div class="col-md-8">
        <div class="card p-3">
            <h6 class="mb-3">Daily Net Sales — This Month (Total: ৳{{ number_format($monthNet, 2) }})</h6>
            <canvas id="dailyChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white fw-semibold" style="border-bottom:1px solid #e5e7eb;">SR Performance — {{ $daysRemaining }} days left in month</div>
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
                backgroundColor: '#2563eb',
                borderRadius: 4,
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
