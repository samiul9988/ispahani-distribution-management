@extends('layouts.app')

@section('title', 'SR-wise Report')

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
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel"></i> Apply</button>
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
                <tr>
                    <th>Sales Rep</th><th>Market</th><th class="text-end">Total Sales</th><th class="text-end">Discount</th>
                    <th class="text-end">Net Sales</th><th class="text-end">Collection</th><th class="text-end">Credit Collection</th><th class="text-end">Closing Route Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row['Sales Rep'] }}</td>
                        <td>{{ $row['Market'] }}</td>
                        <td class="text-end">{{ number_format($row['Total Sales'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Total Discount'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Net Sales'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Collection'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Credit Collection'], 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($row['Closing Route Credit'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
