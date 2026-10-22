<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $staff = Staff::where('is_active', true)->orderBy('name')->get();
        $existing = StaffAttendance::where('attendance_date', $date)->get()->keyBy('staff_id');

        return view('staff_attendance.index', compact('date', 'staff', 'existing'));
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['in:present,absent,leave,half_day'],
        ]);

        foreach ($validated['status'] as $staffId => $status) {
            StaffAttendance::updateOrCreate(
                ['attendance_date' => $validated['date'], 'staff_id' => $staffId],
                ['status' => $status]
            );
        }

        return redirect()->route('staff-attendance.index', ['date' => $validated['date']])->with('success', 'Attendance saved.');
    }
}
