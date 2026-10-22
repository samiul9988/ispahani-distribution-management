@extends('layouts.app')

@section('title', 'Edit Free Offer')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('free-offers.update', $freeOffer) }}">
        @csrf @method('PUT')
        @include('free_offers._form')
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('free-offers.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
