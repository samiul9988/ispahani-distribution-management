@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="staff">Staff</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
