@extends('layouts.app')

@section('title', 'Leave')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('staff-leave.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Request Leave</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Staff</th><th>From</th><th>To</th><th>Reason</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse ($leaves as $l)
                    <tr>
                        <td>{{ $l->staff?->name }}</td>
                        <td>{{ $l->from_date->format('d M Y') }}</td>
                        <td>{{ $l->to_date->format('d M Y') }}</td>
                        <td>{{ $l->reason ?? '—' }}</td>
                        <td>
                            <span class="badge {{ match($l->status) { 'approved' => 'bg-success', 'rejected' => 'bg-danger', default => 'bg-warning text-dark' } }}">{{ ucfirst($l->status) }}</span>
                        </td>
                        <td class="text-end">
                            @if ($l->status === 'pending')
                                <form action="{{ route('staff-leave.status', $l) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-outline-success">Approve</button>
                                </form>
                                <form action="{{ route('staff-leave.status', $l) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No leave requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $leaves->links() }}</div>
@endsection
