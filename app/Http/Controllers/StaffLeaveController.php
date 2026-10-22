<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffLeave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffLeaveController extends Controller
{
    public function index(): View
    {
        $leaves = StaffLeave::with('staff')->orderByDesc('from_date')->paginate(20);

        return view('staff_leave.index', compact('leaves'));
    }

    public function create(): View
    {
        $staff = Staff::where('is_active', true)->orderBy('name')->get();

        return view('staff_leave.create', compact('staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'staff_id' => ['required', 'exists:staff,id'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);
        $data['status'] = 'pending';

        StaffLeave::create($data);

        return redirect()->route('staff-leave.index')->with('success', 'Leave request recorded.');
    }

    public function updateStatus(Request $request, StaffLeave $staffLeave): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:pending,approved,rejected']]);
        $staffLeave->update($data);

        return redirect()->route('staff-leave.index')->with('success', 'Leave status updated.');
    }

    public function destroy(StaffLeave $staffLeave): RedirectResponse
    {
        $staffLeave->delete();

        return redirect()->route('staff-leave.index')->with('success', 'Leave deleted.');
    }
}
