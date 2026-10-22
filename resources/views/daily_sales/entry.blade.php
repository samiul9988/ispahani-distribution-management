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
                        <th style="width:100px;">Discount</th>
                        <th class="text-end">Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php $row = $existing->get($product->id); @endphp
                        <tr class="product-row" data-price="{{ $product->price }}" data-pic="{{ $product->pic }}">
                            <td>{{ $product->name }}</td>
                            <td class="text-end">{{ number_format($product->price, 2) }}</td>
                            <td class="text-end">{{ $product->pic }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-ctn" name="rows[{{ $product->id }}][loading_ctn]" value="{{ $row?->loading_ctn ?? 0 }}"></td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-pcs" name="rows[{{ $product->id }}][loading_pcs]" value="{{ $row?->loading_pcs ?? 0 }}"></td>
                            <td class="text-end out-total">{{ number_format($row?->total_loaded_pcs ?? 0, 2) }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-unload" name="rows[{{ $product->id }}][unloading_pcs]" value="{{ $row?->unloading_pcs ?? 0 }}"></td>
                            <td class="text-end out-sales">{{ number_format($row?->sales_pcs ?? 0, 2) }}</td>
                            <td class="text-end out-taka">{{ number_format($row?->total_taka ?? 0, 2) }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-discount" name="rows[{{ $product->id }}][discount]" value="{{ $row?->discount ?? 0 }}"></td>
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

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Entry</button>
    </div>
</form>
@else
<div class="alert alert-info">Select a date and Sales Rep above to open the entry grid.</div>
@endif
@endsection

@push('scripts')
<script>
function fmt(n) { return (Math.round(n * 100) / 100).toFixed(2); }

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
}

document.addEventListener('input', (e) => {
    if (e.target.matches('.inp-ctn, .inp-pcs, .inp-unload, .inp-discount')) {
        recalcAll();
    }
});

document.addEventListener('DOMContentLoaded', recalcAll);
</script>
@endpush
