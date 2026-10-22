@extends('layouts.app')

@section('title', 'Cash & Credit')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('cash-credit.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="entry_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-pencil-square"></i> Open Entry</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-end">Sales</th>
                    <th class="text-end">Collection</th>
                    <th class="text-end">Credit</th>
                    <th class="text-end">Credit Collection</th>
                    <th class="text-end">Shortage</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summaries as $row)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($row->entry_date)->format('d M Y') }}</td>
                        <td class="text-end">{{ number_format($row->total_sales, 2) }}</td>
                        <td class="text-end">{{ number_format($row->total_collection, 2) }}</td>
                        <td class="text-end">{{ number_format($row->total_credit, 2) }}</td>
                        <td class="text-end">{{ number_format($row->total_credit_collection, 2) }}</td>
                        <td class="text-end {{ $row->total_shortage != 0 ? 'text-danger fw-semibold' : '' }}">{{ number_format($row->total_shortage, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('cash-credit.entry', ['entry_date' => \Illuminate\Support\Carbon::parse($row->entry_date)->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No cash &amp; credit entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $summaries->links() }}</div>
@endsection
