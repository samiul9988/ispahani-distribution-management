@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        @include('products._form')
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('products.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
