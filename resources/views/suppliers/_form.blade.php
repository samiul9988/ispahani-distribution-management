@php $s = $supplier ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Supplier Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $s?->name) }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Company</label>
    <input type="text" name="company" class="form-control" value="{{ old('company', $s?->company) }}">
</div>
<div class="mb-3">
    <label class="form-label">Representative</label>
    <input type="text" name="representative" class="form-control" value="{{ old('representative', $s?->representative) }}">
</div>
<div class="mb-3">
    <label class="form-label">Contact</label>
    <input type="text" name="contact" class="form-control" value="{{ old('contact', $s?->contact) }}">
</div>
<div class="mb-3">
    <label class="form-label">Opening Balance (Due)</label>
    <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', $s?->opening_balance ?? 0) }}">
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $s?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>
