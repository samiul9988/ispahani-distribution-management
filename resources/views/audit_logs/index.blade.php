@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Model</label>
            <select name="model_type" class="form-select">
                <option value="">All</option>
                @foreach ($modelTypes as $type)
                    <option value="{{ $type }}" @selected($modelType === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Action</label>
            <select name="action" class="form-select">
                <option value="">All</option>
                <option value="created" @selected($action === 'created')>Created</option>
                <option value="updated" @selected($action === 'updated')>Updated</option>
                <option value="deleted" @selected($action === 'deleted')>Deleted</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead>
                <tr><th>Time</th><th>User</th><th>Action</th><th>Model</th><th>Description</th><th>Changes</th></tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="text-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td>
                            <span class="badge {{ match($log->action) { 'created' => 'bg-success', 'updated' => 'bg-warning text-dark', 'deleted' => 'bg-danger', default => 'bg-secondary' } }}">{{ $log->action }}</span>
                        </td>
                        <td>{{ $log->model_type }} #{{ $log->model_id }}</td>
                        <td>{{ $log->description }}</td>
                        <td class="small text-muted">
                            @if ($log->changes)
                                {{ \Illuminate\Support\Str::limit(json_encode($log->changes), 120) }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No audit entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $logs->links() }}</div>
@endsection
