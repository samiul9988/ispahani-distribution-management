<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background:#f4f6f9; }
        .sidebar { min-height:100vh; background:#1e293b; }
        .sidebar a { color:#cbd5e1; text-decoration:none; display:block; padding:.6rem 1rem; border-radius:.375rem; }
        .sidebar a.active, .sidebar a:hover { background:#334155; color:#fff; }
        .sidebar .brand { color:#fff; font-weight:600; padding:1rem; border-bottom:1px solid #334155; }
        .card-kpi { border:none; border-radius:.75rem; box-shadow:0 1px 3px rgba(0,0,0,.08); }
        .table thead th { background:#f1f5f9; }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <nav class="sidebar p-2" style="width:240px;">
        <div class="brand">Ispahani Distributor</div>
        <div class="mt-2">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="{{ route('daily-sales.index') }}" class="{{ request()->routeIs('daily-sales.*') ? 'active' : '' }}"><i class="bi bi-journal-text me-2"></i>Daily Sales Entry</a>
            <a href="{{ route('liftings.index') }}" class="{{ request()->routeIs('liftings.*') ? 'active' : '' }}"><i class="bi bi-truck me-2"></i>Lifting / Purchase</a>
            <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.*') ? 'active' : '' }}"><i class="bi bi-boxes me-2"></i>Stock Ledger</a>
            <a href="{{ route('cash-credit.index') }}" class="{{ request()->routeIs('cash-credit.*') ? 'active' : '' }}"><i class="bi bi-cash-coin me-2"></i>Cash & Credit</a>
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line me-2"></i>Reports</a>
            <hr class="text-secondary">
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}"><i class="bi bi-box-seam me-2"></i>Products</a>
            <a href="{{ route('sales-reps.index') }}" class="{{ request()->routeIs('sales-reps.*') ? 'active' : '' }}"><i class="bi bi-person-badge me-2"></i>Sales Reps</a>
            <a href="{{ route('markets.index') }}" class="{{ request()->routeIs('markets.*') ? 'active' : '' }}"><i class="bi bi-map me-2"></i>Markets</a>
            <a href="{{ route('company-profile.edit') }}" class="{{ request()->routeIs('company-profile.*') ? 'active' : '' }}"><i class="bi bi-building me-2"></i>Company Profile</a>
            <a href="{{ route('supplier-payments.index') }}" class="{{ request()->routeIs('supplier-payments.*') ? 'active' : '' }}"><i class="bi bi-receipt me-2"></i>Supplier Payments</a>
            <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}"><i class="bi bi-wallet2 me-2"></i>Expenses</a>
            <hr class="text-secondary">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light w-100 text-start" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
            </form>
        </div>
    </nav>
    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
            <span class="text-muted">{{ auth()->user()?->name }}</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
