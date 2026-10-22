@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="{{ old('description') }}">
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('categories.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
