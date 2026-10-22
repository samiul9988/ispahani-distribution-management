@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel"></i> Load</button>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('staff-attendance.save') }}">
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Staff</th><th>Designation</th><th style="width:300px;">Status</th></tr></thead>
                <tbody>
                    @forelse ($staff as $s)
                        @php $att = $existing->get($s->id); @endphp
                        <tr>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->designation ?? '—' }}</td>
                            <td>
                                <select name="status[{{ $s->id }}]" class="form-select form-select-sm">
                                    <option value="present" @selected(($att?->status ?? 'present') === 'present')>Present</option>
                                    <option value="absent" @selected($att?->status === 'absent')>Absent</option>
                                    <option value="leave" @selected($att?->status === 'leave')>On Leave</option>
                                    <option value="half_day" @selected($att?->status === 'half_day')>Half Day</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No active staff. Add staff first.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($staff->isNotEmpty())
        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-save"></i> Save Attendance</button>
    @endif
</form>
@endsection
