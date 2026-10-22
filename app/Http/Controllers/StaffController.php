<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = Staff::orderBy('name')->paginate(20);

        return view('staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Staff::create($data);

        return redirect()->route('staff.index')->with('success', 'Staff member added.');
    }

    public function edit(Staff $staff): View
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff): RedirectResponse
    {
        $data = $this->validated($request);
        $staff->update($data);

        return redirect()->route('staff.index')->with('success', 'Staff member updated.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'join_date' => ['nullable', 'date'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['monthly_salary'] = $data['monthly_salary'] ?? 0;

        return $data;
    }
}
