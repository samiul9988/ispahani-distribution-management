@extends('layouts.app')

@section('title', 'Record Damaged Stock')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('damaged-stock.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="damage_date" class="form-control" value="{{ old('damage_date', now()->format('Y-m-d')) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Product</label>
            <select name="product_id" class="form-select" required>
                <option value="">— Select —</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} (৳{{ number_format($p->price, 2) }}/pc)</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity (pcs)</label>
            <input type="number" step="0.01" min="0.01" name="qty_pcs" class="form-control" value="{{ old('qty_pcs') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Reason</label>
            <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" placeholder="e.g. Broken in transit, expired">
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('damaged-stock.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
