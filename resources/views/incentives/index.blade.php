@extends('layouts.app')

@section('title', 'Incentives')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div class="fs-5">Total Paid: <strong>৳{{ number_format($total, 2) }}</strong></div>
    <a href="{{ route('incentives.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Incentive</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Date</th><th>Sales Rep</th><th>Reason</th><th class="text-end">Amount</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                @forelse ($incentives as $i)
                    <tr>
                        <td>{{ $i->incentive_date->format('d M Y') }}</td>
                        <td>{{ $i->salesRep?->name }}</td>
                        <td>{{ $i->reason ?? '—' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($i->amount, 2) }}</td>
                        <td class="text-end">
                            <form action="{{ route('incentives.destroy', $i) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No incentives recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $incentives->links() }}</div>
@endsection
