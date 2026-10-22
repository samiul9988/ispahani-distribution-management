@extends('layouts.app')

@section('title', 'Company Profile')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('company-profile.update') }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Distributor Name</label>
            <input type="text" name="distributor_name" class="form-control" value="{{ old('distributor_name', $company->distributor_name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" name="supplier_name" class="form-control" value="{{ old('supplier_name', $company->supplier_name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $company->address) }}">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Supplier Due (Taka)</label>
            <input type="number" step="0.01" name="supplier_due" class="form-control" value="{{ old('supplier_due', $company->supplier_due) }}">
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
