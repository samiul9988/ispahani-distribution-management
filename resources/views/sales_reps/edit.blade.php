@extends('layouts.app')

@section('title', 'Edit Sales Rep')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('sales-reps.update', $salesRep) }}">
        @csrf @method('PUT')
        @include('sales_reps._form')
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('sales-reps.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
