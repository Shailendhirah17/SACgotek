<?php

namespace App\Http\Controllers\UltraSuperAdmin\Communication;

use App\Http\Controllers\Controller;
use App\Models\PlatformAnnouncement;
use App\Models\SchoolGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PlatformBroadcastController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        $announcements = PlatformAnnouncement::with('targetGroup', 'creator')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
                            
        $schoolGroups = SchoolGroup::active()->get();

        return view('backEnd.ultraSuperAdmin.communication.broadcast', compact('announcements', 'schoolGroups'));
    }

    /**
     * Store a newly created broadcast/announcement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:info,warning,critical',
            'target_school_group_id' => 'nullable|exists:school_groups,id',
        ]);

        try {
            $announcement = PlatformAnnouncement::create([
                'title' => $request->title,
                'message' => $request->message,
                'priority' => $request->priority,
                'target_school_group_id' => $request->target_school_group_id,
                'is_published' => $request->has('is_published'),
                'created_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            Log::channel('daily')->info('Platform Broadcast Created', [
                'id' => $announcement->id,
                'priority' => $announcement->priority
            ]);

            return back()->with('message-success', 'Broadcast sent successfully throughout the platform.');
            
        } catch (\Exception $e) {
            Log::error('Broadcast creation failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to dispatch platform broadcast.');
        }
    }
    
    /**
     * Remove the specified announcement.
     */
    public function destroy($id)
    {
        try {
            $announcement = PlatformAnnouncement::findOrFail($id);
            $announcement->delete();
            return back()->with('message-success', 'Broadcast removed successfully.');
        } catch (\Exception $e) {
            return back()->with('message-danger', 'Failed to remove broadcast.');
        }
    }
}
