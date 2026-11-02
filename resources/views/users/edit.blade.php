@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password <span class="text-muted">(leave blank to keep current)</span></label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                <option value="manager" @selected(old('role', $user->role) === 'manager')>Manager</option>
                <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                <option value="sr" @selected(old('role', $user->role) === 'sr')>Sales Rep (SR)</option>
            </select>
            <div class="form-text">Sales Rep logins can only enter their own daily sales — nothing else, unless given extra permissions in Roles &amp; Permissions.</div>
        </div>
        <div class="mb-3" id="salesRepField" style="display:none;">
            <label class="form-label">Linked Sales Rep</label>
            <select name="sales_rep_id" class="form-select">
                <option value="">— Select —</option>
                @foreach ($salesReps as $rep)
                    <option value="{{ $rep->id }}" @selected((string) old('sales_rep_id', $user->sales_rep_id) === (string) $rep->id)>{{ $rep->name }} ({{ $rep->market?->name }})</option>
                @endforeach
            </select>
            <div class="form-text">This user will only be able to view and enter data for this Sales Rep.</div>
        </div>
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const role = document.getElementById('role');
        const field = document.getElementById('salesRepField');
        function sync() { field.style.display = role.value === 'sr' ? '' : 'none'; }
        role.addEventListener('change', sync);
        sync();
    })();
</script>
@endpush
