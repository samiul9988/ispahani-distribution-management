@php $r = $retailer ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $r?->name) }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Shop Name</label>
    <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $r?->shop_name) }}">
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $r?->phone) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Market</label>
        <select name="market_id" class="form-select">
            <option value="">— None —</option>
            @foreach ($markets as $m)
                <option value="{{ $m->id }}" @selected(old('market_id', $r?->market_id) == $m->id)>{{ $m->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Area</label>
    <input type="text" name="area" class="form-control" value="{{ old('area', $r?->area) }}">
</div>
<div class="mb-3">
    <label class="form-label">Address</label>
    <input type="text" name="address" class="form-control" value="{{ old('address', $r?->address) }}">
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $r?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>
