@extends('layouts.app')

@section('title', 'Add Supplier')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        @include('suppliers._form')
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
