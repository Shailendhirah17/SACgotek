<?php

namespace App\Http\Controllers\SuperAdmin\AuditLog;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs with filters.
     */
    public function index(Request $request)
    {
        $query = SuperAdminAuditLog::with('superAdmin');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by entity type
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(30);

        // Get distinct values for filter dropdowns
        $actions = SuperAdminAuditLog::select('action')->distinct()->pluck('action');
        $entityTypes = SuperAdminAuditLog::select('entity_type')->distinct()->whereNotNull('entity_type')->pluck('entity_type');

        return view('backEnd.superAdmin.audit.index', compact('logs', 'actions', 'entityTypes'));
    }

    /**
     * Export audit logs as CSV.
     */
    public function export(Request $request)
    {
        $query = SuperAdminAuditLog::with('superAdmin');

        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $csv = "ID,Admin,Action,Entity,Description,IP,Date\n";
        foreach ($logs as $log) {
            $admin = $log->superAdmin ? $log->superAdmin->username : 'Unknown';
            $desc = str_replace('"', '""', $log->description ?? '');
            $csv .= "{$log->id},\"{$admin}\",{$log->action},{$log->entity_type},\"{$desc}\",{$log->ip_address},{$log->created_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"');
    }
}
