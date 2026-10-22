<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #111; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 0; }
        .meta { display: flex; justify-content: space-between; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 4px 6px; }
        th { background: #f1f5f9; text-align: left; }
        .text-end { text-align: right; }
        tfoot td { font-weight: bold; background: #f8fafc; }
        .signatures { display: flex; justify-content: space-between; margin-top: 50px; }
        .signatures div { text-align: center; width: 30%; border-top: 1px solid #333; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $company?->distributor_name }}</h2>
        <div>{{ $company?->address }}</div>
        <div>Distributor of {{ $company?->supplier_name }}</div>
        <h3 style="margin-top:10px;">DELIVERY CHALLAN</h3>
    </div>

    <div class="meta">
        <div>
            <strong>Date:</strong> {{ \Illuminate\Support\Carbon::parse($saleDate)->format('d M, Y') }}<br>
            <strong>Sales Rep:</strong> {{ $rep->name }}<br>
            <strong>Market/Route:</strong> {{ $rep->market?->name ?? '—' }}
        </div>
        <div>
            <strong>Printed:</strong> {{ now()->format('d M, Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-end">Price</th>
                <th class="text-end">Loaded (pcs)</th>
                <th class="text-end">Returned (pcs)</th>
                <th class="text-end">Sold (pcs)</th>
                <th class="text-end">Taka</th>
                <th class="text-end">Discount</th>
                <th class="text-end">Net</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lines as $i => $line)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $line->product?->name }}</td>
                    <td class="text-end">{{ number_format($line->price_at_sale, 2) }}</td>
                    <td class="text-end">{{ number_format($line->total_loaded_pcs, 2) }}</td>
                    <td class="text-end">{{ number_format($line->unloading_pcs, 2) }}</td>
                    <td class="text-end">{{ number_format($line->sales_pcs, 2) }}</td>
                    <td class="text-end">{{ number_format($line->total_taka, 2) }}</td>
                    <td class="text-end">{{ number_format($line->discount, 2) }}</td>
                    <td class="text-end">{{ number_format($line->net_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td class="text-end">{{ number_format($totals['loaded'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['unloading'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['sales'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['taka'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['discount'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['net'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div>Sales Rep Signature</div>
        <div>Checked By</div>
        <div>Distributor Signature</div>
    </div>
</body>
</html>
