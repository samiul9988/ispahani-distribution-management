@extends('layouts.app')

@section('title', 'Free Offers')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('free-offers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Create Offer</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Product</th><th>Type</th><th>Condition</th><th>Active Window</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($offers as $o)
                    <tr>
                        <td>{{ $o->product?->name }}</td>
                        <td>{{ $o->type === 'free_qty' ? 'Free Qty' : 'Fixed Discount' }}</td>
                        <td>
                            @if ($o->type === 'free_qty')
                                Buy {{ $o->buy_qty }} pcs → Free {{ $o->free_qty }} pcs
                            @else
                                Buy {{ $o->buy_qty }} pcs → ৳{{ number_format($o->discount_amount, 2) }} off
                            @endif
                        </td>
                        <td>{{ $o->starts_at?->format('d M Y') ?? '—' }} to {{ $o->ends_at?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @if ($o->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('free-offers.edit', $o) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('free-offers.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this offer?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No free offers yet. Create an offer to give free pieces or a fixed discount when a product is sold.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $offers->links() }}</div>
@endsection
