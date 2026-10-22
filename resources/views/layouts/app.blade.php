<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #2563eb;
            --brand-soft: #eff6ff;
            --sidebar-w: 260px;
        }
        body { background:#f8fafc; font-family:'Inter', system-ui, sans-serif; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            position: sticky; top: 0;
        }
        .sidebar-brand { padding: 1rem 1.1rem; display:flex; align-items:center; gap:.5rem; border-bottom:1px solid #f1f5f9; }
        .sidebar-brand .logo-badge { width:30px; height:30px; border-radius:8px; background:var(--brand); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.9rem; }
        .sidebar-brand .brand-text { font-weight:700; font-size:1.05rem; color:#111827; }
        .sidebar-scroll { height: calc(100vh - 62px); overflow-y: auto; padding: .75rem .65rem; }
        .nav-group-label { font-size:.68rem; font-weight:700; letter-spacing:.06em; color:#9ca3af; text-transform:uppercase; padding: .9rem .6rem .3rem; }
        .nav-link-custom {
            display:flex; align-items:center; gap:.55rem;
            padding:.45rem .6rem; border-radius:.5rem;
            color:#374151; text-decoration:none; font-size:.875rem; font-weight:500;
            margin-bottom:1px;
        }
        .nav-link-custom i { font-size:1rem; width:18px; text-align:center; color:#6b7280; }
        .nav-link-custom:hover { background:#f3f4f6; color:#111827; }
        .nav-link-custom.active { background:var(--brand-soft); color:var(--brand); }
        .nav-link-custom.active i { color:var(--brand); }
        .sidebar-user { border-top:1px solid #f1f5f9; padding:.75rem; display:flex; align-items:center; gap:.5rem; }
        .sidebar-user .avatar { width:32px; height:32px; border-radius:50%; background:#111827; color:#fff; display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:600; }

        /* Topbar */
        .topbar {
            background:#fff; border-bottom:1px solid #e5e7eb;
            padding:.6rem 1.25rem; display:flex; align-items:center; gap:1rem;
            position: sticky; top:0; z-index:10;
        }
        .search-pill {
            flex:1; max-width:420px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:.6rem;
            padding:.45rem .8rem; display:flex; align-items:center; gap:.5rem; color:#9ca3af; font-size:.85rem;
        }
        .topbar .kbd { font-size:.7rem; background:#fff; border:1px solid #e5e7eb; border-radius:.3rem; padding:.05rem .35rem; color:#9ca3af; }
        .icon-btn { width:36px; height:36px; border-radius:.5rem; border:1px solid #e5e7eb; background:#fff; display:flex; align-items:center; justify-content:center; color:#4b5563; }
        .icon-btn:hover { background:#f3f4f6; }

        /* Content */
        .page-wrap { padding: 1.5rem; }
        .page-greeting .date-line { color:#6b7280; font-size:.85rem; }
        .page-greeting h4 { font-weight:700; color:#111827; }

        .card-kpi { border:1px solid #e5e7eb; border-radius:.85rem; box-shadow:0 1px 2px rgba(0,0,0,.03); background:#fff; }
        .card-kpi .kpi-icon { width:34px; height:34px; border-radius:.5rem; background:var(--brand-soft); color:var(--brand); display:flex; align-items:center; justify-content:center; }

        .card { border:1px solid #e5e7eb; border-radius:.85rem; box-shadow:0 1px 2px rgba(0,0,0,.03); }
        .table thead th { background:#f9fafb; font-size:.75rem; text-transform:uppercase; letter-spacing:.03em; color:#6b7280; font-weight:600; border-bottom:1px solid #e5e7eb; }
        .table td { font-size:.875rem; vertical-align:middle; }
        .btn-primary { background:var(--brand); border-color:var(--brand); }
        .btn-primary:hover { background:#1d4ed8; border-color:#1d4ed8; }

        [data-bs-theme="dark"] body { background:#0f172a; }
        [data-bs-theme="dark"] .sidebar,
        [data-bs-theme="dark"] .topbar,
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .card-kpi { background:#1e293b; border-color:#334155; }
        [data-bs-theme="dark"] .nav-link-custom { color:#cbd5e1; }
        [data-bs-theme="dark"] .nav-link-custom:hover { background:#334155; color:#fff; }
        [data-bs-theme="dark"] .search-pill { background:#0f172a; border-color:#334155; }
        [data-bs-theme="dark"] .table thead th { background:#0f172a; color:#94a3b8; border-color:#334155; }
        [data-bs-theme="dark"] .sidebar-brand .brand-text,
        [data-bs-theme="dark"] .page-greeting h4 { color:#f1f5f9; }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <nav class="sidebar d-none d-lg-block">
        <div class="sidebar-brand">
            <div class="logo-badge">I</div>
            <div class="brand-text">Ispahani Distributor</div>
        </div>
        <div class="sidebar-scroll">
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i>Dashboard</a>

            <div class="nav-group-label">Masters</div>
            <a href="#productsSub" data-bs-toggle="collapse" class="nav-link-custom {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>Products <i class="bi bi-chevron-down ms-auto" style="font-size:.7rem;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('products.*') ? 'show' : '' }}" id="productsSub">
                <a href="{{ route('products.index') }}" class="nav-link-custom ps-4 {{ request()->routeIs('products.index') ? 'active' : '' }}">All Products</a>
                <a href="{{ route('products.create') }}" class="nav-link-custom ps-4 {{ request()->routeIs('products.create') ? 'active' : '' }}">Add Product</a>
                <a href="{{ route('products.multi-add') }}" class="nav-link-custom ps-4 {{ request()->routeIs('products.multi-add') ? 'active' : '' }}">Multi Add Product</a>
                <a href="{{ route('products.low-stock') }}" class="nav-link-custom ps-4 {{ request()->routeIs('products.low-stock') ? 'active' : '' }}">Low Stock</a>
                <a href="{{ route('products.bulk-edit') }}" class="nav-link-custom ps-4 {{ request()->routeIs('products.bulk-edit') ? 'active' : '' }}">Bulk Operations</a>
            </div>
            <a href="{{ route('pack-types.index') }}" class="nav-link-custom {{ request()->routeIs('pack-types.*') ? 'active' : '' }}"><i class="bi bi-rulers"></i>Pack & Quantity Types</a>
            <a href="{{ route('categories.index') }}" class="nav-link-custom {{ request()->routeIs('categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i>Categories</a>
            <a href="{{ route('suppliers.index') }}" class="nav-link-custom {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"><i class="bi bi-building"></i>Suppliers</a>
            <a href="{{ route('retailers.index') }}" class="nav-link-custom {{ request()->routeIs('retailers.*') ? 'active' : '' }}"><i class="bi bi-people"></i>Customers</a>
            <a href="{{ route('sales-reps.index') }}" class="nav-link-custom {{ request()->routeIs('sales-reps.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i>Sales Reps (DSR)</a>
            <a href="{{ route('markets.index') }}" class="nav-link-custom {{ request()->routeIs('markets.*') ? 'active' : '' }}"><i class="bi bi-map"></i>Markets</a>

            <div class="nav-group-label">Operations</div>
            <a href="#purchaseSub" data-bs-toggle="collapse" class="nav-link-custom {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt-cutoff"></i>Purchase Orders <i class="bi bi-chevron-down ms-auto" style="font-size:.7rem;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('purchase-orders.*') ? 'show' : '' }}" id="purchaseSub">
                <a href="{{ route('purchase-orders.index') }}" class="nav-link-custom ps-4 {{ request()->routeIs('purchase-orders.index') ? 'active' : '' }}">All Purchase Orders</a>
                <a href="{{ route('purchase-orders.create') }}" class="nav-link-custom ps-4 {{ request()->routeIs('purchase-orders.create') ? 'active' : '' }}">Create Purchase Order</a>
            </div>
            <a href="{{ route('daily-sales.index') }}" class="nav-link-custom {{ request()->routeIs('daily-sales.*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i>Sales</a>
            <a href="{{ route('liftings.index') }}" class="nav-link-custom {{ request()->routeIs('liftings.*') ? 'active' : '' }}"><i class="bi bi-box-arrow-in-down"></i>Lifting (Quick Entry)</a>
            <a href="{{ route('stock.index') }}" class="nav-link-custom {{ request()->routeIs('stock.*') ? 'active' : '' }}"><i class="bi bi-boxes"></i>Stock Ledger</a>
            <a href="{{ route('free-offers.index') }}" class="nav-link-custom {{ request()->routeIs('free-offers.*') ? 'active' : '' }}"><i class="bi bi-gift"></i>Free Offers</a>
            <a href="{{ route('damaged-stock.index') }}" class="nav-link-custom {{ request()->routeIs('damaged-stock.*') ? 'active' : '' }}"><i class="bi bi-exclamation-triangle"></i>Damaged Stock</a>
            <a href="{{ route('damage-sendings.index') }}" class="nav-link-custom {{ request()->routeIs('damage-sendings.*') ? 'active' : '' }}"><i class="bi bi-box-arrow-up"></i>Damage Sendings</a>

            <div class="nav-group-label">Accounting</div>
            <a href="{{ route('cash-credit.index') }}" class="nav-link-custom {{ request()->routeIs('cash-credit.*') ? 'active' : '' }}"><i class="bi bi-cash-coin"></i>Cash & Credit</a>
            <a href="{{ route('cash-box.index') }}" class="nav-link-custom {{ request()->routeIs('cash-box.*') ? 'active' : '' }}"><i class="bi bi-safe"></i>Cash Box</a>
            <a href="{{ route('supplier-payments.index') }}" class="nav-link-custom {{ request()->routeIs('supplier-payments.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i>Supplier Payment</a>
            <a href="{{ route('expenses.index') }}" class="nav-link-custom {{ request()->routeIs('expenses.*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i>Expenses</a>

            <div class="nav-group-label">Reports & System</div>
            <a href="{{ route('reports.index') }}" class="nav-link-custom {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
            <a href="{{ route('audit-logs.index') }}" class="nav-link-custom {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}"><i class="bi bi-clock-history"></i>Audit Log</a>
            <a href="{{ route('company-profile.edit') }}" class="nav-link-custom {{ request()->routeIs('company-profile.*') ? 'active' : '' }}"><i class="bi bi-gear"></i>Company Profile</a>
        </div>
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</div>
            <div class="flex-grow-1" style="min-width:0;">
                <div class="small fw-semibold text-truncate">{{ auth()->user()?->name }}</div>
                <div class="small text-muted text-truncate" style="font-size:.72rem;">{{ auth()->user()?->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="icon-btn border-0 bg-transparent" type="submit" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </nav>

    <div class="flex-grow-1" style="min-width:0;">
        <div class="topbar">
            <button class="icon-btn d-lg-none" type="button"><i class="bi bi-list"></i></button>
            <div class="search-pill">
                <i class="bi bi-search"></i>
                <span>Search everything...</span>
                <span class="ms-auto kbd">⌘K</span>
            </div>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="{{ route('liftings.entry') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-flex align-items-center gap-1"><i class="bi bi-box-seam"></i> Add Stock</a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-flex align-items-center gap-1"><i class="bi bi-bar-chart"></i> Reports</a>
                <a href="{{ route('daily-sales.entry') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"><i class="bi bi-plus-lg"></i> New Sale</a>
                <button class="icon-btn" id="themeToggle" type="button" title="Toggle theme"><i class="bi bi-moon"></i></button>
                <button class="icon-btn position-relative" type="button" title="Notifications"><i class="bi bi-bell"></i></button>
            </div>
        </div>

        <div class="page-wrap">
            <div class="page-greeting mb-3">
                <div class="date-line">{{ now()->format('l, F j, Y') }}</div>
                @if (request()->routeIs('dashboard'))
                    @php
                        $hour = now()->hour;
                        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
                    @endphp
                    <h4 class="mb-0">{{ $greeting }}, {{ auth()->user()?->name }}</h4>
                @else
                    <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
                @endif
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
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const root = document.documentElement;
        const toggle = document.getElementById('themeToggle');
        const saved = localStorage.getItem('theme') || 'light';
        if (saved === 'dark') {
            root.setAttribute('data-bs-theme', 'dark');
            toggle.innerHTML = '<i class="bi bi-sun"></i>';
        }
        toggle?.addEventListener('click', () => {
            const isDark = root.getAttribute('data-bs-theme') === 'dark';
            if (isDark) {
                root.removeAttribute('data-bs-theme');
                localStorage.setItem('theme', 'light');
                toggle.innerHTML = '<i class="bi bi-moon"></i>';
            } else {
                root.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                toggle.innerHTML = '<i class="bi bi-sun"></i>';
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>
