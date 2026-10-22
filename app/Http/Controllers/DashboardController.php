<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Product;
use App\Models\SalesRep;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $company = CompanyProfile::first();
        $productCount = Product::count();
        $salesRepCount = SalesRep::where('is_active', true)->count();

        return view('dashboard.index', [
            'company' => $company,
            'productCount' => $productCount,
            'salesRepCount' => $salesRepCount,
        ]);
    }
}
