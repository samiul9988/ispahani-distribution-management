<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\DailySale;
use App\Models\SalesRep;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChallanController extends Controller
{
    public function show(Request $request): Response
    {
        $data = $request->validate([
            'sale_date' => ['required', 'date'],
            'sales_rep_id' => ['required', 'exists:sales_reps,id'],
        ]);

        $rep = SalesRep::with('market')->findOrFail($data['sales_rep_id']);
        $company = CompanyProfile::first();

        $lines = DailySale::where('sale_date', $data['sale_date'])
            ->where('sales_rep_id', $data['sales_rep_id'])
            ->where('total_loaded_pcs', '>', 0)
            ->with('product')
            ->get();

        $totals = [
            'loaded' => $lines->sum('total_loaded_pcs'),
            'unloading' => $lines->sum('unloading_pcs'),
            'sales' => $lines->sum('sales_pcs'),
            'taka' => $lines->sum('total_taka'),
            'discount' => $lines->sum('discount'),
            'net' => $lines->sum('net_amount'),
        ];

        $html = view('challan.show', [
            'company' => $company,
            'rep' => $rep,
            'saleDate' => $data['sale_date'],
            'lines' => $lines,
            'totals' => $totals,
        ])->render();

        return Pdf::loadHTML($html)->stream('challan-'.$rep->name.'-'.$data['sale_date'].'.pdf');
    }
}
