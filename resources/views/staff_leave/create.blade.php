@extends('layouts.app')

@section('title', 'Request Leave')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('staff-leave.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Staff</label>
            <select name="staff_id" class="form-select" required>
                @foreach ($staff as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">From</label>
                <input type="date" name="from_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">To</label>
                <input type="date" name="to_date" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Reason</label>
            <input type="text" name="reason" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('staff-leave.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
