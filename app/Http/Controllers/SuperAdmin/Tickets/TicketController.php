<?php

namespace App\Http\Controllers\SuperAdmin\Tickets;

use App\Http\Controllers\Controller;
use App\Models\SaasTicket;
use App\Models\SaasTicketCategory;
use App\Models\SaasTicketReply;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $query = SaasTicket::with(['school', 'category', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->orderBy('updated_at', 'desc')->paginate(20);

        return view('backEnd.superAdmin.tickets.index', compact('tickets'));
    }

    /**
     * Display the specified ticket with replies.
     */
    public function show($id)
    {
        $ticket = SaasTicket::with(['school', 'category', 'assignedTo', 'replies.user', 'replies.superAdmin'])
                            ->findOrFail($id);

        return view('backEnd.superAdmin.tickets.show', compact('ticket'));
    }

    /**
     * Reply to a ticket.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        try {
            $ticket = SaasTicket::findOrFail($id);
            $currentAdmin = Auth::guard('superadmin')->user();

            SaasTicketReply::create([
                'ticket_id' => $ticket->id,
                'super_admin_id' => $currentAdmin->id,
                'reply' => $request->reply,
                'is_staff' => 1
            ]);

            // Update ticket status to answered if it was open or pending
            if (in_array($ticket->status, ['open', 'pending'])) {
                $ticket->update(['status' => 'answered']);
            }
            
            // Touch the ticket to update its updated_at column
            $ticket->touch();

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'replied',
                'Ticket',
                $ticket->id,
                "Replied to ticket #{$ticket->id}"
            );

            return back()->with('message-success', 'Reply posted successfully.');

        } catch (\Exception $e) {
            Log::error('Ticket reply failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to post reply.');
        }
    }

    /**
     * Update ticket status (e.g. close ticket).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,pending,answered,closed'
        ]);

        try {
            $ticket = SaasTicket::findOrFail($id);
            $currentAdmin = Auth::guard('superadmin')->user();
            $oldStatus = $ticket->status;

            $ticket->update([
                'status' => $request->status,
                'super_admin_id' => $currentAdmin->id // Auto assign to staff who closes/changes it
            ]);

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'status_changed',
                'Ticket',
                $ticket->id,
                "Changed ticket #{$ticket->id} status from {$oldStatus} to {$request->status}"
            );

            return back()->with('message-success', 'Ticket status updated.');

        } catch (\Exception $e) {
            Log::error('Ticket status update failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to update ticket status.');
        }
    }
}
