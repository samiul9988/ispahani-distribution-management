@extends('layouts.app')

@section('title', 'Record Supplier Payment')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('supplier-payments.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount (Taka)</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Method</label>
            <select name="method" class="form-select">
                <option value="Cash" selected>Cash</option>
                <option value="Bank">Bank</option>
                <option value="bKash">bKash</option>
                <option value="Nagad">Nagad</option>
                <option value="Cheque">Cheque</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control" value="{{ old('note') }}">
        </div>
        <button class="btn btn-primary" type="submit">Save Payment</button>
        <a href="{{ route('supplier-payments.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
