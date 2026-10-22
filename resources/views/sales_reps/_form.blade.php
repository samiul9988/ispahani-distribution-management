@php $rep = $salesRep; @endphp

<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $rep?->name) }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $rep?->phone) }}">
</div>
<div class="mb-3">
    <label class="form-label">Market</label>
    <select name="market_id" class="form-select">
        <option value="">— None —</option>
        @foreach ($markets as $market)
            <option value="{{ $market->id }}" @selected(old('market_id', $rep?->market_id) == $market->id)>{{ $market->name }}</option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Opening Credit (Taka)</label>
        <input type="number" step="0.01" name="opening_credit" class="form-control" value="{{ old('opening_credit', $rep?->opening_credit ?? 0) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Monthly Sales Target (Taka)</label>
        <input type="number" step="0.01" name="sales_target" class="form-control" value="{{ old('sales_target', $rep?->sales_target ?? 0) }}">
    </div>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $rep?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>
