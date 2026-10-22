<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffSalary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffSalaryController extends Controller
{
    public function index(): View
    {
        $salaries = StaffSalary::with('staff')->orderByDesc('salary_month')->paginate(20);
        $staff = Staff::where('is_active', true)->orderBy('name')->get();

        return view('staff_salary.index', compact('salaries', 'staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'staff_id' => ['required', 'exists:staff,id'],
            'salary_month' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        StaffSalary::updateOrCreate(
            ['staff_id' => $data['staff_id'], 'salary_month' => date('Y-m-01', strtotime($data['salary_month']))],
            ['amount' => $data['amount'], 'status' => 'pending']
        );

        return redirect()->route('staff-salary.index')->with('success', 'Salary entry saved.');
    }

    public function markPaid(StaffSalary $staffSalary): RedirectResponse
    {
        $staffSalary->update(['status' => 'paid', 'paid_date' => now()->format('Y-m-d')]);

        return redirect()->route('staff-salary.index')->with('success', 'Marked as paid.');
    }
}
