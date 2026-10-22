@php $p = $product ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $p?->name) }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Category</label>
    <input type="text" name="category" class="form-control" value="{{ old('category', $p?->category) }}" placeholder="e.g. Tea, Snacks">
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Selling Price (per piece/unit)</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $p?->price ?? 0) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">PIC (pieces per carton)</label>
        <input type="number" name="pic" class="form-control" value="{{ old('pic', $p?->pic ?? 1) }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Cost Price (per piece, from Ispahani)</label>
    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $p?->cost_price ?? 0) }}">
    <div class="form-text">Used to calculate profit on the dashboard. Leave 0 if unknown.</div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Opening Stock (Carton)</label>
        <input type="number" step="0.01" name="opening_stock_ctn" class="form-control" value="{{ old('opening_stock_ctn', $p?->opening_stock_ctn ?? 0) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Opening Stock (Pcs)</label>
        <input type="number" step="0.01" name="opening_stock_pcs" class="form-control" value="{{ old('opening_stock_pcs', $p?->opening_stock_pcs ?? 0) }}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Low Stock Alert Threshold (Carton)</label>
    <input type="number" name="low_stock_threshold_ctn" class="form-control" value="{{ old('low_stock_threshold_ctn', $p?->low_stock_threshold_ctn ?? 5) }}">
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $p?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>
