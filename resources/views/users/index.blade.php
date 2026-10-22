@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add User</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($u->role) }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            @if ($u->id !== auth()->id())
                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
