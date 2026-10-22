@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('retailers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Customer</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Name</th><th>Shop</th><th>Phone</th><th>Area</th><th>Market</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($retailers as $r)
                    <tr>
                        <td>{{ $r->name }}</td>
                        <td>{{ $r->shop_name ?? '—' }}</td>
                        <td>{{ $r->phone ?? '—' }}</td>
                        <td>{{ $r->area ?? '—' }}</td>
                        <td>{{ $r->market?->name ?? '—' }}</td>
                        <td>
                            @if ($r->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('retailers.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('retailers.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No customers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $retailers->links() }}</div>
@endsection
