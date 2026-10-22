@extends('layouts.app')

@section('title', 'Cash & Credit Entry')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('cash-credit.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="entry_date" class="form-control" value="{{ $entryDate }}" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-arrow-repeat"></i> Switch</button>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('cash-credit.save') }}" id="ccForm">
    @csrf
    <input type="hidden" name="entry_date" value="{{ $entryDate }}">

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Sales Rep</th>
                        <th class="text-end">Opening Balance</th>
                        <th class="text-end">Today's Sales</th>
                        <th style="width:130px;">Collection</th>
                        <th class="text-end">Credit (today)</th>
                        <th style="width:130px;">Credit Collection</th>
                        <th class="text-end">Route Credit (closing)</th>
                        <th style="width:130px;">Route Shortage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $r)
                        @php $existing = $r['existing']; @endphp
                        <tr class="rep-row" data-sales="{{ $r['sales_amount'] }}" data-opening="{{ $r['opening_balance'] }}">
                            <td>{{ $r['rep']->name }}</td>
                            <td class="text-end out-opening">{{ number_format($r['opening_balance'], 2) }}</td>
                            <td class="text-end out-sales">{{ number_format($r['sales_amount'], 2) }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-collection" name="rows[{{ $r['rep']->id }}][collection]" value="{{ $existing?->collection ?? 0 }}"></td>
                            <td class="text-end out-credit">{{ number_format($existing?->credit ?? 0, 2) }}</td>
                            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm inp-crcollection" name="rows[{{ $r['rep']->id }}][credit_collection]" value="{{ $existing?->credit_collection ?? 0 }}"></td>
                            <td class="text-end out-routecredit fw-semibold">{{ number_format($existing?->route_credit ?? 0, 2) }}</td>
                            <td><input type="number" step="0.01" class="form-control form-control-sm inp-shortage" name="rows[{{ $r['rep']->id }}][route_shortage]" value="{{ $existing?->route_shortage ?? 0 }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Entry</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function fmt(n) { return (Math.round(n * 100) / 100).toFixed(2); }

function recalcRow(row) {
    const sales = parseFloat(row.dataset.sales) || 0;
    const opening = parseFloat(row.dataset.opening) || 0;
    const collection = parseFloat(row.querySelector('.inp-collection').value) || 0;
    const crCollection = parseFloat(row.querySelector('.inp-crcollection').value) || 0;

    const credit = Math.max(0, sales - collection);
    const routeCredit = opening + credit - crCollection;

    row.querySelector('.out-credit').textContent = fmt(credit);
    row.querySelector('.out-routecredit').textContent = fmt(routeCredit);
}

document.addEventListener('input', (e) => {
    if (e.target.matches('.inp-collection, .inp-crcollection')) {
        recalcRow(e.target.closest('.rep-row'));
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.rep-row').forEach(recalcRow);
});
</script>
@endpush
