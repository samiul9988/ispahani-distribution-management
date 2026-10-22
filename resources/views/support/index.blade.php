@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <h5 class="mb-3"><i class="bi bi-headset"></i> Need Help?</h5>
    <p class="text-muted">For system issues or feature requests, contact your system administrator.</p>
    <ul class="list-unstyled">
        <li class="mb-2"><i class="bi bi-envelope me-2"></i> {{ $company?->email ?? 'support@example.com' }}</li>
        <li class="mb-2"><i class="bi bi-telephone me-2"></i> {{ $company?->phone ?? '—' }}</li>
        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> {{ $company?->address ?? '—' }}</li>
    </ul>
</div>
@endsection
