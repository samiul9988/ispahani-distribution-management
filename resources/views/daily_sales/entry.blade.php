@extends('layouts.app')

@section('title', 'Daily Sales Entry')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('daily-sales.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="sale_date" class="form-control" value="{{ $saleDate }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Sales Rep</label>
            <select name="sales_rep_id" class="form-select" required>
                <option value="">— Select SR —</option>
                @foreach ($salesReps as $rep)
                    <option value="{{ $rep->id }}" @selected($salesRepId == $rep->id)>{{ $rep->name }} ({{ $rep->market?->name }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-arrow-repeat"></i> Switch</button>
        </div>
        @if ($salesRepId)
            <div class="col-md-3 text-end">
                <a href="{{ route('daily-sales.challan', ['sale_date' => $saleDate, 'sales_rep_id' => $salesRepId]) }}" target="_blank" class="btn btn-outline-danger"><i class="bi bi-printer"></i> Print Challan</a>
            </div>
        @endif
    </form>
</div>

@if ($salesRepId)
<form method="POST" action="{{ route('daily-sales.save') }}" id="entryForm">
    @csrf
    <input type="hidden" name="sale_date" value="{{ $saleDate }}">
    <input type="hidden" name="sales_rep_id" value="{{ $salesRepId }}">

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="min-width:180px;">Product</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">PIC</th>
                        <th style="width:90px;">Load Ctn</th>
                        <th style="width:90px;">Load Pcs</th>
                        <th class="text-end">Total Loaded</th>
                        <th style="width:90px;">Unloading</th>
                        <th class="text-end">Sales Qty</th>
                        <th class="text-end">Total Taka</th>
                        <th style="width:140px;">Discount</th>
                        <th class="text-end">Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php
                            $row = $existing->get($product->id);
                            $offer = $offers->get($product->id);
                        @endphp
                        <tr class="product-row" data-price="{{ $product->price }}" data-pic="{{ $product->pic }}"
                            @if ($offer)
                                data-offer-type="{{ $offer->type }}"
                                data-offer-buy-qty="{{ $offer->buy_qty }}"
                                data-offer-free-qty="{{ $offer->free_qty }}"
                                data-offer-discount-amount="{{ $offer->discount_amount }}"
                            @endif
                        >
                            <td>
                                {{ $product->name }}
                                @if ($offer)
                                    <br><span class="badge bg-info text-dark"><i class="bi bi-gift"></i>
                                        @if ($offer->type === 'free_qty')
                                            Buy {{ $offer->buy_qty }} get {{ $offer->free_qty }} free
                                        @else
                                            Buy {{ $offer->buy_qty }} get ৳{{ $offer->discount_amount }} off
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($product->price, 2) }}</td>
                            <td class="text-end">{{ $product->pic }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-ctn" name="rows[{{ $product->id }}][loading_ctn]" value="{{ $row?->loading_ctn ?? 0 }}"></td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-pcs" name="rows[{{ $product->id }}][loading_pcs]" value="{{ $row?->loading_pcs ?? 0 }}"></td>
                            <td class="text-end out-total">{{ number_format($row?->total_loaded_pcs ?? 0, 2) }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-unload" name="rows[{{ $product->id }}][unloading_pcs]" value="{{ $row?->unloading_pcs ?? 0 }}"></td>
                            <td class="text-end out-sales">{{ number_format($row?->sales_pcs ?? 0, 2) }}</td>
                            <td class="text-end out-taka">{{ number_format($row?->total_taka ?? 0, 2) }}</td>
                            <td>
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm inp-discount" name="rows[{{ $product->id }}][discount]" value="{{ $row?->discount ?? 0 }}">
                                @if ($offer)
                                    <button type="button" class="btn btn-sm btn-outline-info mt-1 btn-apply-offer" style="font-size:.7rem;">Apply offer</button>
                                @endif
                            </td>
                            <td class="text-end out-net fw-semibold">{{ number_format($row?->net_amount ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="8" class="text-end">Grand Total</td>
                        <td class="text-end" id="grandTaka">0.00</td>
                        <td></td>
                        <td class="text-end" id="grandNet">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card p-3 mt-3">
        <h6 class="mb-3"><i class="bi bi-cash-coin"></i> Payment & Credit (same transaction)</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label text-muted small">Opening Balance</label>
                <div class="fs-5" id="openingBalanceDisplay">{{ number_format($openingBalance, 2) }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Net Sales (this entry)</label>
                <div class="fs-5" id="netSalesDisplay">0.00</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Collection (Cash received today)</label>
                <input type="number" step="0.01" min="0" class="form-control" id="inpCollection" name="collection" value="{{ $existingCredit?->collection ?? 0 }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Credit Collection (old due paid)</label>
                <input type="number" step="0.01" min="0" class="form-control" id="inpCrCollection" name="credit_collection" value="{{ $existingCredit?->credit_collection ?? 0 }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Credit (today, unpaid)</label>
                <div class="fs-5 text-warning" id="creditDisplay">{{ number_format($existingCredit?->credit ?? 0, 2) }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Route Credit (closing balance)</label>
                <div class="fs-5 fw-bold text-danger" id="routeCreditDisplay">{{ number_format($existingCredit?->route_credit ?? 0, 2) }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Route Shortage</label>
                <input type="number" step="0.01" class="form-control" name="route_shortage" value="{{ $existingCredit?->route_shortage ?? 0 }}">
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Save Sale (Stock + Cash & Credit)</button>
    </div>
</form>
@else
<div class="alert alert-info">Select a date and Sales Rep above to open the entry grid.</div>
@endif
@endsection

@push('scripts')
<script>
const openingBalance = {{ $openingBalance ?? 0 }};
function fmt(n) { return (Math.round(n * 100) / 100).toFixed(2); }

function recalcCredit(netSales) {
    const collection = parseFloat(document.getElementById('inpCollection')?.value) || 0;
    const crCollection = parseFloat(document.getElementById('inpCrCollection')?.value) || 0;
    const credit = Math.max(0, netSales - collection);
    const routeCredit = openingBalance + credit - crCollection;

    const netDisplay = document.getElementById('netSalesDisplay');
    const creditDisplay = document.getElementById('creditDisplay');
    const routeCreditDisplay = document.getElementById('routeCreditDisplay');
    if (netDisplay) netDisplay.textContent = fmt(netSales);
    if (creditDisplay) creditDisplay.textContent = fmt(credit);
    if (routeCreditDisplay) routeCreditDisplay.textContent = fmt(routeCredit);
}

function recalcRow(row) {
    const price = parseFloat(row.dataset.price) || 0;
    const pic = parseFloat(row.dataset.pic) || 0;
    const ctn = parseFloat(row.querySelector('.inp-ctn').value) || 0;
    const pcs = parseFloat(row.querySelector('.inp-pcs').value) || 0;
    const unload = parseFloat(row.querySelector('.inp-unload').value) || 0;
    const discount = parseFloat(row.querySelector('.inp-discount').value) || 0;

    const totalLoaded = (ctn * pic) + pcs;
    const sales = Math.max(0, totalLoaded - unload);
    const taka = sales * price;
    const net = taka - discount;

    row.querySelector('.out-total').textContent = fmt(totalLoaded);
    row.querySelector('.out-sales').textContent = fmt(sales);
    row.querySelector('.out-taka').textContent = fmt(taka);
    row.querySelector('.out-net').textContent = fmt(net);

    return { taka, net };
}

function recalcAll() {
    let grandTaka = 0, grandNet = 0;
    document.querySelectorAll('.product-row').forEach((row) => {
        const { taka, net } = recalcRow(row);
        grandTaka += taka;
        grandNet += net;
    });
    const gt = document.getElementById('grandTaka');
    const gn = document.getElementById('grandNet');
    if (gt) gt.textContent = fmt(grandTaka);
    if (gn) gn.textContent = fmt(grandNet);
    recalcCredit(grandNet);
}

document.addEventListener('input', (e) => {
    if (e.target.matches('.inp-ctn, .inp-pcs, .inp-unload, .inp-discount, #inpCollection, #inpCrCollection')) {
        recalcAll();
    }
});

document.addEventListener('click', (e) => {
    if (e.target.matches('.btn-apply-offer')) {
        const row = e.target.closest('.product-row');
        const price = parseFloat(row.dataset.price) || 0;
        const salesPcs = parseFloat(row.querySelector('.out-sales').textContent) || 0;
        const buyQty = parseFloat(row.dataset.offerBuyQty) || 0;
        const type = row.dataset.offerType;

        if (buyQty <= 0) return;
        const triggers = Math.floor(salesPcs / buyQty);
        let discount = 0;
        if (triggers > 0) {
            if (type === 'free_qty') {
                const freeQty = parseFloat(row.dataset.offerFreeQty) || 0;
                discount = triggers * freeQty * price;
            } else {
                const discountAmount = parseFloat(row.dataset.offerDiscountAmount) || 0;
                discount = triggers * discountAmount;
            }
        }

        row.querySelector('.inp-discount').value = fmt(discount);
        recalcAll();
    }
});

document.addEventListener('DOMContentLoaded', recalcAll);
</script>
@endpush
