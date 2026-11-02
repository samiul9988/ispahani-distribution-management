@php $s = $staff ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $s?->name) }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Designation</label>
    <input type="text" name="designation" class="form-control" value="{{ old('designation', $s?->designation) }}" placeholder="e.g. Delivery Staff, Accountant">
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $s?->phone) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Join Date</label>
        <input type="date" name="join_date" class="form-control" value="{{ old('join_date', $s?->join_date?->format('Y-m-d')) }}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Monthly Salary</label>
    <input type="number" step="0.01" name="monthly_salary" class="form-control" value="{{ old('monthly_salary', $s?->monthly_salary ?? 0) }}">
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $s?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>
