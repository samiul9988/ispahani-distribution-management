@extends('layouts.app')

@section('title', 'Add Incentive')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('incentives.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="incentive_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Sales Rep</label>
            <select name="sales_rep_id" class="form-select" required>
                @foreach ($salesReps as $rep)
                    <option value="{{ $rep->id }}">{{ $rep->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Reason</label>
            <input type="text" name="reason" class="form-control" placeholder="e.g. Target achievement bonus">
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('incentives.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
