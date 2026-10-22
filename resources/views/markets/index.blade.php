@extends('layouts.app')

@section('title', 'Markets')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('markets.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Market</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Sales Reps</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($markets as $market)
                    <tr>
                        <td>{{ $market->name }}</td>
                        <td>{{ $market->sales_reps_count }}</td>
                        <td>
                            @if ($market->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('markets.edit', $market) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('markets.destroy', $market) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this market?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No markets yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $markets->links() }}</div>
@endsection
