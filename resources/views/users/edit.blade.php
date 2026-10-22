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
            <select name="role" class="form-select" required>
                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                <option value="manager" @selected($user->role === 'manager')>Manager</option>
                <option value="staff" @selected($user->role === 'staff')>Staff</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
