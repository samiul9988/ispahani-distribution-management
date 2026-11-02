@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="card p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="mb-1">Roles & Permissions</h5>
            <p class="text-muted mb-0">Choose which menus and features <strong>Manager</strong> and <strong>Staff</strong> users can access. <strong>Admin</strong> always has full access.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('role-permissions.update') }}">
        @csrf @method('PUT')

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="min-width:220px;">Module</th>
                        @foreach ($roles as $role)
                            <th class="text-center text-capitalize">{{ $role === 'sr' ? 'SR' : $role }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $groupName => $permissions)
                        <tr class="table-light">
                            <td colspan="{{ count($roles) + 1 }}" class="fw-semibold">{{ $groupName }}</td>
                        </tr>
                        @foreach ($permissions as $key => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                @foreach ($roles as $role)
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            name="permissions[{{ $role }}][]"
                                            value="{{ $key }}"
                                            @checked(in_array($key, $granted[$role] ?? []))
                                        >
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn btn-primary mt-2" type="submit">Save Permissions</button>
    </form>
</div>
@endsection
