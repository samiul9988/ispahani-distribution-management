<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $modelType = $request->get('model_type');
        $action = $request->get('action');

        $logs = AuditLog::query()
            ->with('user')
            ->when($modelType, fn ($q) => $q->where('model_type', $modelType))
            ->when($action, fn ($q) => $q->where('action', $action))
            ->orderByDesc('created_at')
            ->paginate(30);

        $modelTypes = AuditLog::query()->distinct()->orderBy('model_type')->pluck('model_type');

        return view('audit_logs.index', compact('logs', 'modelTypes', 'modelType', 'action'));
    }
}
