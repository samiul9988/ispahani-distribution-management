@extends('layouts.app')

@section('title', 'Lifting / Purchase')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('liftings.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Lifting Date</label>
            <input type="date" name="lifting_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
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
                    <th class="text-end">Total Pcs Lifted</th>
                    <th class="text-end">Total Value (Taka)</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summaries as $row)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($row->lifting_date)->format('d M Y') }}</td>
                        <td class="text-end">{{ number_format($row->total_pcs, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($row->total_value, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('liftings.entry', ['lifting_date' => \Illuminate\Support\Carbon::parse($row->lifting_date)->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No lifting entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $summaries->links() }}</div>
@endsection
