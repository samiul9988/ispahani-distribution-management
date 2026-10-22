@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Supplier</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Supplier</th><th>Company</th><th>Representative</th><th>Contact</th><th class="text-end">Opening Balance</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $s)
                    <tr>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->company ?? '—' }}</td>
                        <td>{{ $s->representative ?? '—' }}</td>
                        <td>{{ $s->contact ?? '—' }}</td>
                        <td class="text-end">{{ number_format($s->opening_balance, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('suppliers.edit', $s) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('suppliers.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No suppliers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $suppliers->links() }}</div>
@endsection
