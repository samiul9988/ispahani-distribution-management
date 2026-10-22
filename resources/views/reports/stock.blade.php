@extends('layouts.app')

@section('title', 'Stock Report')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">As of Date</label>
            <input type="date" name="as_of" class="form-control" value="{{ $asOf }}">
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
                <tr><th>Product</th><th class="text-end">Opening</th><th class="text-end">Lifted</th><th class="text-end">Sold</th><th class="text-end">Closing (pcs)</th><th class="text-end">Closing (ctn)</th><th class="text-end">Stock Value</th></tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row['Product'] }}</td>
                        <td class="text-end">{{ number_format($row['Opening (pcs)'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Lifted (pcs)'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Sold (pcs)'], 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($row['Closing (pcs)'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Closing (ctn)'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['Stock Value'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
