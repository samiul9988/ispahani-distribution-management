<div class="row g-2">
    <div class="col-md-4">
        <label class="form-label small">Name</label>
        <input type="text" name="products[{{ $index }}][name]" class="form-control form-control-sm" required>
    </div>
    <div class="col-md-2">
        <label class="form-label small">Category</label>
        <select name="products[{{ $index }}][category_id]" class="form-select form-select-sm">
            <option value="">—</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-1">
        <label class="form-label small">Price</label>
        <input type="number" step="0.01" name="products[{{ $index }}][price]" class="form-control form-control-sm" required>
    </div>
    <div class="col-md-1">
        <label class="form-label small">Cost</label>
        <input type="number" step="0.01" name="products[{{ $index }}][cost_price]" class="form-control form-control-sm">
    </div>
    <div class="col-md-1">
        <label class="form-label small">PIC</label>
        <input type="number" name="products[{{ $index }}][pic]" class="form-control form-control-sm" value="1" required>
    </div>
    <div class="col-md-1">
        <label class="form-label small">Open Ctn</label>
        <input type="number" step="0.01" name="products[{{ $index }}][opening_stock_ctn]" class="form-control form-control-sm" value="0">
    </div>
    <div class="col-md-2">
        <label class="form-label small">Low Stock (ctn)</label>
        <input type="number" name="products[{{ $index }}][low_stock_threshold_ctn]" class="form-control form-control-sm" value="5">
    </div>
</div>
