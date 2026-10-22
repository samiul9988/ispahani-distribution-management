@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="alert alert-warning">
    <strong>{{ $title }}</strong> module is coming in the next build step.
</div>
@endsection
