@extends('layouts.app')

@section('title', 'Edit Staff')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('staff.update', $staff) }}">
        @csrf @method('PUT')
        @include('staff._form')
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('staff.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
