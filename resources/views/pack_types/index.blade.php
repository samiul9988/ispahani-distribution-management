@extends('layouts.app')

@section('title', 'Pack & Quantity Types')

@section('content')
<p class="text-muted">Manage the Pack Type and Quantity Type lists available when adding products. Default (System) options cannot be removed.</p>

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#packTab">Pack Types ({{ $packTypes->count() }})</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#qtyTab">Quantity Types ({{ $quantityTypes->count() }})</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="packTab">
        <form method="POST" action="{{ route('pack-types.store-pack') }}" class="d-flex gap-2 mb-3" style="max-width:400px;">
            @csrf
            <input type="text" name="value" class="form-control" placeholder="e.g. Sachet" required>
            <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add</button>
        </form>
        <div class="card">
            <table class="table mb-0">
                <thead><tr><th>Value</th><th>Source</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @foreach ($packTypes as $p)
                        <tr>
                            <td>{{ $p->value }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($p->source) }}</span></td>
                            <td class="text-end">
                                @if ($p->source !== 'system')
                                    <form action="{{ route('pack-types.destroy-pack', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade" id="qtyTab">
        <form method="POST" action="{{ route('pack-types.store-quantity') }}" class="d-flex gap-2 mb-3" style="max-width:400px;">
            @csrf
            <input type="text" name="value" class="form-control" placeholder="e.g. Sack" required>
            <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add</button>
        </form>
        <div class="card">
            <table class="table mb-0">
                <thead><tr><th>Value</th><th>Source</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @foreach ($quantityTypes as $q)
                        <tr>
                            <td>{{ $q->value }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($q->source) }}</span></td>
                            <td class="text-end">
                                @if ($q->source !== 'system')
                                    <form action="{{ route('pack-types.destroy-quantity', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
