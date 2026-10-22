@extends('layouts.app')

@section('title', 'Lifting / Purchase Entry')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('liftings.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Lifting Date</label>
            <input type="date" name="lifting_date" class="form-control" value="{{ $liftingDate }}" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-arrow-repeat"></i> Switch</button>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('liftings.save') }}" id="liftingForm">
    @csrf
    <input type="hidden" name="lifting_date" value="{{ $liftingDate }}">

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="min-width:180px;">Product</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">PIC</th>
                        <th style="width:100px;">Lift Ctn</th>
                        <th style="width:100px;">Lift Pcs</th>
                        <th class="text-end">Total Lifted Pcs</th>
                        <th style="width:140px;">Value (Taka)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php $row = $existing->get($product->id); @endphp
                        <tr class="product-row" data-pic="{{ $product->pic }}" data-price="{{ $product->price }}">
                            <td>{{ $product->name }}</td>
                            <td class="text-end">{{ number_format($product->price, 2) }}</td>
                            <td class="text-end">{{ $product->pic }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-ctn" name="rows[{{ $product->id }}][lifting_ctn]" value="{{ $row?->lifting_ctn ?? 0 }}"></td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-pcs" name="rows[{{ $product->id }}][lifting_pcs]" value="{{ $row?->lifting_pcs ?? 0 }}"></td>
                            <td class="text-end out-total">{{ number_format($row?->total_lifted_pcs ?? 0, 2) }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-value" name="rows[{{ $product->id }}][value]" value="{{ $row?->value ?? 0 }}"></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="5" class="text-end">Grand Total</td>
                        <td class="text-end" id="grandTotalPcs">0.00</td>
                        <td class="text-end" id="grandValue">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Lifting</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function fmt(n) { return (Math.round(n * 100) / 100).toFixed(2); }

function recalcRow(row) {
    const pic = parseFloat(row.dataset.pic) || 0;
    const ctn = parseFloat(row.querySelector('.inp-ctn').value) || 0;
    const pcs = parseFloat(row.querySelector('.inp-pcs').value) || 0;
    const value = parseFloat(row.querySelector('.inp-value').value) || 0;

    const total = (ctn * pic) + pcs;
    row.querySelector('.out-total').textContent = fmt(total);

    return { total, value };
}

function recalcAll() {
    let grandTotal = 0, grandValue = 0;
    document.querySelectorAll('#liftingForm .product-row').forEach((row) => {
        const { total, value } = recalcRow(row);
        grandTotal += total;
        grandValue += value;
    });
    document.getElementById('grandTotalPcs').textContent = fmt(grandTotal);
    document.getElementById('grandValue').textContent = fmt(grandValue);
}

document.addEventListener('input', (e) => {
    if (e.target.matches('.inp-ctn, .inp-pcs, .inp-value')) {
        recalcAll();
    }
});

document.addEventListener('DOMContentLoaded', recalcAll);
</script>
@endpush
