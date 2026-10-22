@php $o = $freeOffer ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Product</label>
    <select name="product_id" class="form-select" required>
        <option value="">— Select —</option>
        @foreach ($products as $p)
            <option value="{{ $p->id }}" @selected(old('product_id', $o?->product_id) == $p->id)>{{ $p->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Offer Type</label>
    <select name="type" class="form-select" id="offerType" required>
        <option value="free_qty" @selected(old('type', $o?->type) === 'free_qty')>Free Quantity (Buy X Get Y Free)</option>
        <option value="fixed_discount" @selected(old('type', $o?->type) === 'fixed_discount')>Fixed Discount (Taka off per trigger)</option>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Buy Quantity (pcs) to trigger</label>
    <input type="number" step="0.01" min="0.01" name="buy_qty" class="form-control" value="{{ old('buy_qty', $o?->buy_qty) }}" required>
</div>
<div class="mb-3" id="freeQtyField">
    <label class="form-label">Free Quantity (pcs) given</label>
    <input type="number" step="0.01" min="0" name="free_qty" class="form-control" value="{{ old('free_qty', $o?->free_qty) }}">
</div>
<div class="mb-3" id="discountField">
    <label class="form-label">Fixed Discount (Taka) per trigger</label>
    <input type="number" step="0.01" min="0" name="discount_amount" class="form-control" value="{{ old('discount_amount', $o?->discount_amount) }}">
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Starts At (optional)</label>
        <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', $o?->starts_at?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Ends At (optional)</label>
        <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at', $o?->ends_at?->format('Y-m-d')) }}">
    </div>
</div>
<div class="mb-3 form-check">
    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $o?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<script>
    function toggleOfferFields() {
        const type = document.getElementById('offerType').value;
        document.getElementById('freeQtyField').style.display = type === 'free_qty' ? '' : 'none';
        document.getElementById('discountField').style.display = type === 'fixed_discount' ? '' : 'none';
    }
    document.getElementById('offerType').addEventListener('change', toggleOfferFields);
    document.addEventListener('DOMContentLoaded', toggleOfferFields);
</script>
