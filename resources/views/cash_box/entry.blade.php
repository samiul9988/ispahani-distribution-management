@extends('layouts.app')

@section('title', 'Cash Box Entry')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('cash-box.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="cash_date" class="form-control" value="{{ $cashDate }}" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-arrow-repeat"></i> Switch</button>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('cash-box.save') }}" id="cashBoxForm">
    @csrf
    <input type="hidden" name="cash_date" value="{{ $cashDate }}">

    <div class="card p-4" style="max-width:700px;">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Opening Balance</label>
                <input type="number" step="0.01" class="form-control" id="inpOpening" name="opening_balance" value="{{ $existing?->opening_balance ?? $openingBalance }}">
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small">Collection (auto, from Cash &amp; Credit)</label>
                <div class="fs-5 text-success">+ {{ number_format($totals['collection'], 2) }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small">Credit Collection (auto)</label>
                <div class="fs-5 text-success">+ {{ number_format($totals['credit_collection'], 2) }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small">Expenses (auto)</label>
                <div class="fs-5 text-danger">- {{ number_format($totals['expense'], 2) }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small">Supplier Payments (auto)</label>
                <div class="fs-5 text-danger">- {{ number_format($totals['supplier_payment'], 2) }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Adjustment (+/- cash variance)</label>
                <input type="number" step="0.01" class="form-control" id="inpAdjustment" name="adjustment" value="{{ $existing?->adjustment ?? 0 }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Note</label>
                <input type="text" class="form-control" name="note" value="{{ $existing?->note }}">
            </div>
            <div class="col-12">
                <hr>
                <label class="form-label text-muted small">Closing Balance</label>
                <div class="fs-3 fw-bold" id="closingDisplay">0.00</div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-save"></i> Save / Close Day</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
const collection = {{ $totals['collection'] }};
const creditCollection = {{ $totals['credit_collection'] }};
const expense = {{ $totals['expense'] }};
const supplierPayment = {{ $totals['supplier_payment'] }};

function fmt(n) { return (Math.round(n * 100) / 100).toFixed(2); }

function recalc() {
    const opening = parseFloat(document.getElementById('inpOpening').value) || 0;
    const adjustment = parseFloat(document.getElementById('inpAdjustment').value) || 0;
    const closing = opening + collection + creditCollection - expense - supplierPayment + adjustment;
    document.getElementById('closingDisplay').textContent = fmt(closing);
}

document.addEventListener('input', (e) => {
    if (e.target.matches('#inpOpening, #inpAdjustment')) recalc();
});
document.addEventListener('DOMContentLoaded', recalc);
</script>
@endpush
