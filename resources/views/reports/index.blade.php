@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="row g-3">
    @foreach ([
        ['route' => 'reports.daily-sales', 'icon' => 'calendar2-week', 'title' => 'Daily Sales Report', 'desc' => 'Date-range sales by day & SR, totals, discount, net.'],
        ['route' => 'reports.sr-wise', 'icon' => 'person-badge', 'title' => 'SR-wise Report', 'desc' => 'Per-SR sales, collection, and closing credit balance.'],
        ['route' => 'reports.product-wise', 'icon' => 'box-seam', 'title' => 'Product-wise Report', 'desc' => 'Per-product sales qty, taka, discount, net.'],
        ['route' => 'reports.stock', 'icon' => 'boxes', 'title' => 'Stock Report', 'desc' => 'Opening, lifted, sold, closing stock & valuation as of a date.'],
        ['route' => 'reports.credit', 'icon' => 'cash-coin', 'title' => 'Credit & Collection Report', 'desc' => 'Daily cash/credit ledger per SR over a date range.'],
    ] as $card)
        <div class="col-md-4">
            <a href="{{ route($card['route']) }}" class="text-decoration-none">
                <div class="card card-kpi p-3 h-100">
                    <i class="bi bi-{{ $card['icon'] }} fs-3 text-primary mb-2"></i>
                    <div class="fw-semibold">{{ $card['title'] }}</div>
                    <div class="text-muted small">{{ $card['desc'] }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>
@endsection
