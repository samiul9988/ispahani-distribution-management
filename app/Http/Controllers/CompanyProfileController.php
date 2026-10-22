<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(): View
    {
        $company = CompanyProfile::firstOrCreate([], ['distributor_name' => 'Distributor Name']);

        return view('company_profile.edit', compact('company'));
    }

    public function update(Request $request): RedirectResponse
    {
        $company = CompanyProfile::firstOrFail();

        $data = $request->validate([
            'distributor_name' => ['required', 'string', 'max:255'],
            'supplier_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'supplier_due' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['supplier_due'] = $data['supplier_due'] ?? 0;

        $company->update($data);

        return redirect()->route('company-profile.edit')->with('success', 'Company profile updated.');
    }
}
