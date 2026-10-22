@extends('layouts.app')

@section('title', 'Daily Sales Report')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sales Rep</label>
            <select name="sales_rep_id" class="form-select">
                <option value="">All</option>
                @foreach ($salesReps as $rep)
                    <option value="{{ $rep->id }}" @selected($repId == $rep->id)>{{ $rep->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill" type="submit"><i class="bi bi-funnel"></i> Apply</button>
        </div>
    </form>
    <div class="mt-2">
        <a class="btn btn-sm btn-outline-success" href="{{ request()->fullUrlWithQuery(['format' => 'xlsx']) }}"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
        <a class="btn btn-sm btn-outline-danger" href="{{ request()->fullUrlWithQuery(['format' => 'pdf']) }}"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead>
                <tr><th>Date</th><th>Sales Rep</th><th class="text-end">Total Taka</th><th class="text-end">Discount</th><th class="text-end">Net Amount</th></tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row['Date'] }}</td>
                        <td>{{ $row['Sales Rep'] }}</td>
                        <td class="text-end">{{ number_format($row['Total Taka'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Discount'], 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($row['Net Amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No data for this range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
