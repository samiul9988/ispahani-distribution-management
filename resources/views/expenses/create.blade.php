@extends('layouts.app')

@section('title', 'Add Expense')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Expense Date</label>
            <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', now()->format('Y-m-d')) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <option value="Transport">Transport</option>
                <option value="Fuel">Fuel</option>
                <option value="Salary">Salary</option>
                <option value="Rent">Rent</option>
                <option value="Utility">Utility</option>
                <option value="Loading/Unloading">Loading/Unloading</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Office">Office</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="{{ old('description') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Amount (Taka)</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
        </div>
        <button class="btn btn-primary" type="submit">Save Expense</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
