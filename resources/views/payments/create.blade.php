@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('payments.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Direction</label>
            <select name="direction" class="form-select" required>
                <option value="in">Money In</option>
                <option value="out">Money Out</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Party Name</label>
            <input type="text" name="party_name" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Method</label>
            <select name="method" class="form-select">
                <option>Cash</option>
                <option>Bank</option>
                <option>bKash</option>
                <option>Nagad</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('payments.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
