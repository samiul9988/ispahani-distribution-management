@extends('layouts.app')

@section('title', 'Add Sales Rep')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('sales-reps.store') }}">
        @csrf
        @include('sales_reps._form', ['salesRep' => null])
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('sales-reps.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
