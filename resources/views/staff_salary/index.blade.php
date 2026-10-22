@extends('layouts.app')

@section('title', 'Staff Salary')

@section('content')
<div class="card p-3 mb-4">
    <h6 class="mb-3">Add / Update Salary Entry</h6>
    <form method="POST" action="{{ route('staff-salary.store') }}" class="row g-2 align-items-end">
        @csrf
        <div class="col-md-4">
            <label class="form-label small">Staff</label>
            <select name="staff_id" class="form-select" required>
                @foreach ($staff as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Month</label>
            <input type="month" name="salary_month" class="form-control" value="{{ now()->format('Y-m') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Save</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Staff</th><th>Month</th><th class="text-end">Amount</th><th>Status</th><th>Paid Date</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                @forelse ($salaries as $sal)
                    <tr>
                        <td>{{ $sal->staff?->name }}</td>
                        <td>{{ $sal->salary_month->format('F Y') }}</td>
                        <td class="text-end">{{ number_format($sal->amount, 2) }}</td>
                        <td><span class="badge {{ $sal->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($sal->status) }}</span></td>
                        <td>{{ $sal->paid_date?->format('d M Y') ?? '—' }}</td>
                        <td class="text-end">
                            @if ($sal->status === 'pending')
                                <form action="{{ route('staff-salary.mark-paid', $sal) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">Mark Paid</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No salary entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $salaries->links() }}</div>
@endsection
