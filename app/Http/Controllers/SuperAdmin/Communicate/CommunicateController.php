<?php

namespace App\Http\Controllers\SuperAdmin\Communicate;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use App\SmSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use App\Models\SchoolGroup;

class CommunicateController extends Controller
{
    /**
     * Display the communications hub.
     */
    public function index()
    {
        $currentAdmin = Auth::guard('superadmin')->user();

        // Safely check if school_group_id column exists before filtering
        $hasGroupCol = Schema::hasColumn('sm_schools', 'school_group_id');
        $hasGroupIdOnAdmin = $hasGroupCol && !empty($currentAdmin->school_group_id);

        $schoolQuery = SmSchool::query();

        // Only filter by status if the column exists
        if ($hasGroupCol && Schema::hasColumn('sm_schools', 'active_status')) {
            $schoolQuery->where('active_status', 1);
        }

        // Hierarchy Filtering: If this admin is scoped to a group, show only their schools
        if ($hasGroupIdOnAdmin) {
            $schoolQuery->where('school_group_id', $currentAdmin->school_group_id);
            $schoolGroups = SchoolGroup::where('id', $currentAdmin->school_group_id)->get();
        } else {
            // Global admin — show all school groups
            try {
                $schoolGroups = SchoolGroup::where('active_status', 1)->get();
            } catch (\Exception $e) {
                $schoolGroups = collect(); // table may not exist yet
            }
        }

        $schools = $schoolQuery->orderBy('school_name')->get();

        $sentMessages = SuperAdminAuditLog::where('action', 'communication_sent')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        Log::debug('CommunicateController@index', [
            'total_schools' => $schools->count(),
            'schools_with_email' => $schools->whereNotNull('email')->where('email', '!=', '')->count(),
            'school_groups' => $schoolGroups->count(),
            'admin_group' => $currentAdmin->school_group_id ?? null,
        ]);

        return view('backEnd.superAdmin.communicate.index', compact('schools', 'schoolGroups', 'sentMessages'));
    }

    /**
     * Send email to selected schools.
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'subject'          => 'required|string|max:200',
            'message'          => 'required|string',
            'recipients'       => 'required|string|in:all,organization,selected',
            'school_ids'       => 'nullable|array',
            'school_ids.*'     => 'integer',
            'organization_ids' => 'nullable|array',
            'organization_ids.*' => 'integer',
        ]);

        try {
            $currentAdmin = Auth::guard('superadmin')->user();
            $hasGroupCol  = Schema::hasColumn('sm_schools', 'school_group_id');

            // ── Build the schools collection based on recipient type ──
            if ($request->recipients === 'all') {
                $query = SmSchool::where('active_status', 1);
                // Restrict to admin's group if applicable
                if ($hasGroupCol && !empty($currentAdmin->school_group_id)) {
                    $query->where('school_group_id', $currentAdmin->school_group_id);
                }
                $schools = $query->get();

            } elseif ($request->recipients === 'organization') {
                $orgIds = $request->input('organization_ids', []);
                if (empty($orgIds)) {
                    return back()->with('message-danger', 'Please select at least one organization.');
                }
                $query = SmSchool::where('active_status', 1);
                if ($hasGroupCol) {
                    $query->whereIn('school_group_id', $orgIds);
                }
                $schools = $query->get();

            } else {
                // 'selected' — use explicit school IDs
                $schoolIds = $request->input('school_ids', []);
                if (empty($schoolIds)) {
                    return back()->with('message-danger', 'Please select at least one school.');
                }
                $schools = SmSchool::whereIn('id', $schoolIds)->get();
            }

            Log::debug('CommunicateController@sendEmail', [
                'recipients_type' => $request->recipients,
                'schools_found'   => $schools->count(),
                'school_ids_raw'  => $request->input('school_ids'),
                'org_ids_raw'     => $request->input('organization_ids'),
            ]);

            if ($schools->isEmpty()) {
                return back()->with('message-danger',
                    'No schools found for the selected criteria. ' .
                    'If you chose "All Schools", check that schools are added via School Management.'
                );
            }

            $sentCount    = 0;
            $skippedCount = 0;
            $failedCount  = 0;

            foreach ($schools as $school) {
                $email = trim($school->email ?? '');

                if (empty($email)) {
                    // School has no email address — skip silently
                    $skippedCount++;
                    Log::debug("Skipped school (no email): {$school->school_name}");
                    continue;
                }

                try {
                    Mail::raw($request->message, function ($mail) use ($school, $request, $email) {
                        $mail->to($email)->subject($request->subject);
                    });
                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::warning("Failed to send email to {$school->school_name} <{$email}>", [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // ── Log to audit trail ──
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'communication_sent',
                'Email',
                null,
                "Sent email '{$request->subject}': {$sentCount} sent, {$skippedCount} skipped (no email), {$failedCount} failed",
                null,
                [
                    'subject'   => $request->subject,
                    'sent'      => $sentCount,
                    'skipped'   => $skippedCount,
                    'failed'    => $failedCount,
                    'total'     => $schools->count(),
                ]
            );

            // ── Build response message ──
            if ($sentCount === 0) {
                if ($skippedCount > 0 && $failedCount === 0) {
                    return back()->with('message-danger',
                        "Could not send emails — {$skippedCount} out of {$schools->count()} schools have no email address set. " .
                        "Please update school email addresses in School Management."
                    );
                }
                if ($failedCount > 0) {
                    return back()->with('message-danger',
                        "Mail server error — emails failed to send to all {$failedCount} schools. " .
                        "Please check your MAIL settings in the .env file (MAIL_USERNAME and MAIL_PASSWORD must be set)."
                    );
                }
                return back()->with('message-danger', 'No emails were sent. Please check school records and mail configuration.');
            }

            $msg = "✓ Email sent successfully to {$sentCount} " . ($sentCount === 1 ? 'school' : 'schools') . ".";
            if ($skippedCount > 0) $msg .= " ({$skippedCount} skipped — no email address)";
            if ($failedCount > 0)  $msg .= " ({$failedCount} failed — mail server error)";

            return back()->with('message-success', $msg);

        } catch (\Exception $e) {
            Log::error('CommunicateController@sendEmail exception', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('message-danger', 'System error: ' . $e->getMessage());
        }
    }

    /**
     * Send platform announcement/notice.
     */
    public function sendNotice(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'message' => 'required|string',
            'type'    => 'required|string|in:info,warning,critical',
        ]);

        try {
            $currentAdmin = Auth::guard('superadmin')->user();

            $notices = json_decode(\App\Models\SuperAdminSetting::get('platform_notices', '[]'), true) ?? [];
            $notices[] = [
                'id'         => uniqid(),
                'title'      => $request->title,
                'message'    => $request->message,
                'type'       => $request->type,
                'created_by' => $currentAdmin->full_name,
                'created_at' => now()->toIso8601String(),
            ];

            $notices = array_slice($notices, -50);
            \App\Models\SuperAdminSetting::set('platform_notices', json_encode($notices), 'communication', 'json');

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'communication_sent',
                'Notice',
                null,
                "Published platform notice: {$request->title}",
                null,
                ['title' => $request->title, 'type' => $request->type]
            );

            return back()->with('message-success', 'Notice published successfully.');

        } catch (\Exception $e) {
            Log::error('Notice publishing failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to publish notice.');
        }
    }
}
