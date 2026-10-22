@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Distributor</div>
            <div class="fs-5 fw-semibold">{{ $company?->distributor_name ?? '—' }}</div>
            <div class="text-muted small">{{ $company?->address }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Active Products (SKUs)</div>
            <div class="fs-3 fw-bold text-primary">{{ $productCount }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi p-3">
            <div class="text-muted small">Active Sales Reps</div>
            <div class="fs-3 fw-bold text-success">{{ $salesRepCount }}</div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    Masters module ready. Daily Sales, Lifting, Stock Ledger, and Cash &amp; Credit modules are being built next —
    their menu links already exist and will light up as each module ships.
</div>
@endsection
