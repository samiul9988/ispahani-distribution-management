@extends('layouts.app')

@section('title', 'Multi Add Product')

@section('content')
<form method="POST" action="{{ route('products.multi-store') }}" id="multiForm">
    @csrf
    <div id="rows">
        <div class="card p-3 mb-3 product-row-block">
            <h6 class="mb-3">Product #1</h6>
            @include('products._multi_row', ['index' => 0])
        </div>
    </div>

    <button type="button" class="btn btn-outline-primary mb-3" id="addRowBtn"><i class="bi bi-plus-lg"></i> Add Another Product</button>
    <div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save All Products</button>
        <a href="{{ route('products.index') }}" class="btn btn-link">Cancel</a>
    </div>
</form>

<template id="rowTemplate">
    <div class="card p-3 mb-3 product-row-block">
        <h6 class="mb-3 row-title">Product #__INDEX__</h6>
        @include('products._multi_row', ['index' => '__INDEX__'])
    </div>
</template>
@endsection

@push('scripts')
<script>
    let rowCount = 1;
    document.getElementById('addRowBtn').addEventListener('click', () => {
        const template = document.getElementById('rowTemplate').innerHTML.replaceAll('__INDEX__', rowCount);
        document.getElementById('rows').insertAdjacentHTML('beforeend', template);
        rowCount++;
    });
</script>
@endpush
