@extends('layouts.app')

@section('title', 'Sales Reps')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('sales-reps.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Sales Rep</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Market</th>
                    <th>Opening Credit</th>
                    <th>Sales Target</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salesReps as $rep)
                    <tr>
                        <td>{{ $rep->name }}</td>
                        <td>{{ $rep->phone ?? '—' }}</td>
                        <td>{{ $rep->market?->name ?? '—' }}</td>
                        <td>{{ number_format($rep->opening_credit, 2) }}</td>
                        <td>{{ number_format($rep->sales_target, 2) }}</td>
                        <td>
                            @if ($rep->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('sales-reps.edit', $rep) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('sales-reps.destroy', $rep) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this sales rep?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No sales reps yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $salesReps->links() }}</div>
@endsection
