@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('retailers.update', $retailer) }}">
        @csrf @method('PUT')
        @include('retailers._form')
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('retailers.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
