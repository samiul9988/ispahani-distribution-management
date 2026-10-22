@extends('layouts.app')

@section('title', 'Send Damaged Stock to Supplier')

@section('content')
<div class="card p-4" style="max-width:700px;">
    <form method="POST" action="{{ route('damage-sendings.store') }}">
        @csrf
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Sending Date</label>
                <input type="date" name="sending_date" class="form-control" value="{{ old('sending_date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Supplier</label>
                <select name="supplier_id" class="form-select" required>
                    @foreach ($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <label class="form-label">Select damaged stock to send</label>
        <div class="card mb-3">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th></th><th>Date</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Value</th></tr></thead>
                    <tbody>
                        @forelse ($pendingDamages as $d)
                            <tr>
                                <td><input type="checkbox" name="damaged_stock_ids[]" value="{{ $d->id }}"></td>
                                <td>{{ $d->damage_date->format('d M Y') }}</td>
                                <td>{{ $d->product?->name }}</td>
                                <td class="text-end">{{ number_format($d->qty_pcs, 2) }}</td>
                                <td class="text-end">{{ number_format($d->value, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Nothing pending to send.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control" value="{{ old('note') }}">
        </div>

        <button class="btn btn-primary" type="submit">Send Selected</button>
        <a href="{{ route('damage-sendings.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
