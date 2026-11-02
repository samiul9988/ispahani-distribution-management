@extends('layouts.app')

@section('title', 'Add Market')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('markets.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Market Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('markets.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
