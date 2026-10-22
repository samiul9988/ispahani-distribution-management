@extends('layouts.app')

@section('title', 'Daily Sales Entry')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('daily-sales.entry') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="sale_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Sales Rep</label>
            <select name="sales_rep_id" class="form-select" required>
                <option value="">— Select SR —</option>
                @foreach ($salesReps as $rep)
                    <option value="{{ $rep->id }}">{{ $rep->name }} ({{ $rep->market?->name }})</option>
                @endforeach
            </select>
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
                    <th>Sales Rep</th>
                    <th class="text-end">Total Taka</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">Net Amount</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summaries as $row)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($row->sale_date)->format('d M Y') }}</td>
                        <td>{{ $row->salesRep?->name }}</td>
                        <td class="text-end">{{ number_format($row->total_taka, 2) }}</td>
                        <td class="text-end">{{ number_format($row->total_discount, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($row->total_net, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('daily-sales.entry', ['sale_date' => \Illuminate\Support\Carbon::parse($row->sale_date)->format('Y-m-d'), 'sales_rep_id' => $row->sales_rep_id]) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No daily sales entries yet. Use the form above to start.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $summaries->links() }}</div>
@endsection
