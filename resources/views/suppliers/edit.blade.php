@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
        @csrf @method('PUT')
        @include('suppliers._form')
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
