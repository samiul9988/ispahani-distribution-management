@extends('layouts.app')

@section('title', 'New Purchase Order')

@section('content')
<p class="text-muted">Record goods received from a supplier. "Receive & Save" books the stock in immediately; "Save as Draft" raises the order without moving stock.</p>

<form method="POST" action="{{ route('purchase-orders.store') }}" id="poForm">
    @csrf
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card p-3 mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="po_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" placeholder="PO-...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select supplier</option>
                            @foreach ($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option>Supplier Balance</option>
                            <option>Cash</option>
                            <option>Bank</option>
                            <option>bKash</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Note</label>
                        <input type="text" name="note" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="itemsTable">
                        <thead>
                            <tr><th>Product</th><th style="width:90px;">Ctn</th><th style="width:90px;">Pcs</th><th class="text-end">Total Pcs</th><th style="width:110px;">Buy Price</th><th class="text-end">Total Cost</th><th></th></tr>
                        </thead>
                        <tbody id="itemsBody"></tbody>
                    </table>
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn"><i class="bi bi-plus-lg"></i> Add Product</button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h6>Payment Summary</h6>
                <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="subtotalDisplay">৳0.00</strong></div>
                <div class="mb-3">
                    <label class="form-label">Payment Amount</label>
                    <input type="number" step="0.01" name="payment_amount" id="paymentAmount" class="form-control" value="0">
                </div>
                <button type="submit" name="action" value="receive" class="btn btn-primary mb-2"><i class="bi bi-check2-circle"></i> Receive & Save</button>
                <button type="submit" name="action" value="draft" class="btn btn-outline-secondary"><i class="bi bi-file-earmark"></i> Save as Draft</button>
            </div>
        </div>
    </div>
</form>

<template id="itemRowTemplate">
    <tr class="item-row">
        <td>
            <select class="form-select form-select-sm item-product" name="items[__INDEX__][product_id]" required>
                <option value="">Select product</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" data-pic="{{ $p->pic }}" data-cost="{{ $p->cost_price }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" step="0.01" min="0" class="form-control form-control-sm item-ctn" name="items[__INDEX__][qty_ctn]" value="0"></td>
        <td><input type="number" step="0.01" min="0" class="form-control form-control-sm item-pcs" name="items[__INDEX__][qty_pcs]" value="0"></td>
        <td class="text-end item-total-pcs">0.00</td>
        <td><input type="number" step="0.01" min="0" class="form-control form-control-sm item-price" name="items[__INDEX__][buy_price]" value="0"></td>
        <td class="text-end item-total-cost">0.00</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="bi bi-trash"></i></button></td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
let itemIndex = 0;

function fmt(n) { return (Math.round(n * 100) / 100).toFixed(2); }

function addItemRow() {
    const html = document.getElementById('itemRowTemplate').innerHTML.replaceAll('__INDEX__', itemIndex);
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', html);
    itemIndex++;
}

document.getElementById('addItemBtn').addEventListener('click', addItemRow);
addItemRow();

function recalcRow(row) {
    const select = row.querySelector('.item-product');
    const option = select.selectedOptions[0];
    const pic = option ? (parseFloat(option.dataset.pic) || 0) : 0;
    const cost = option ? (parseFloat(option.dataset.cost) || 0) : 0;

    const priceInput = row.querySelector('.item-price');
    if (priceInput.value == 0 && option) priceInput.value = cost;

    const ctn = parseFloat(row.querySelector('.item-ctn').value) || 0;
    const pcs = parseFloat(row.querySelector('.item-pcs').value) || 0;
    const price = parseFloat(priceInput.value) || 0;

    const totalPcs = (ctn * pic) + pcs;
    const totalCost = totalPcs * price;

    row.querySelector('.item-total-pcs').textContent = fmt(totalPcs);
    row.querySelector('.item-total-cost').textContent = fmt(totalCost);

    return totalCost;
}

function recalcAll() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach((row) => { subtotal += recalcRow(row); });
    document.getElementById('subtotalDisplay').textContent = '৳' + fmt(subtotal);
}

document.addEventListener('input', (e) => {
    if (e.target.matches('.item-ctn, .item-pcs, .item-price')) recalcAll();
});
document.addEventListener('change', (e) => {
    if (e.target.matches('.item-product')) recalcAll();
});
document.addEventListener('click', (e) => {
    if (e.target.closest('.remove-item')) {
        e.target.closest('tr').remove();
        recalcAll();
    }
});
</script>
@endpush
