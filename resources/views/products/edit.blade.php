@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf @method('PUT')
        @include('products._form')
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
