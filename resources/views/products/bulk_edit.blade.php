@extends('layouts.app')

@section('title', 'Bulk Edit Stock')

@section('content')
<p class="text-muted">Edit multiple products inline and update all at once.</p>

<form method="POST" action="{{ route('products.bulk-update') }}">
    @csrf
    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead>
                    <tr><th>Product</th><th style="width:140px;">Price</th><th style="width:140px;">Cost Price</th><th style="width:140px;">Low Stock (ctn)</th></tr>
                </thead>
                <tbody>
                    @foreach ($products as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td><input type="number" step="0.01" class="form-control form-control-sm" name="products[{{ $p->id }}][price]" value="{{ $p->price }}"></td>
                            <td><input type="number" step="0.01" class="form-control form-control-sm" name="products[{{ $p->id }}][cost_price]" value="{{ $p->cost_price }}"></td>
                            <td><input type="number" class="form-control form-control-sm" name="products[{{ $p->id }}][low_stock_threshold_ctn]" value="{{ $p->low_stock_threshold_ctn }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-save"></i> Update All</button>
</form>
@endsection
