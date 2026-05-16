<?php

namespace Modules\SmartCommunication\Http\Controllers;

use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SmartCommunicationController extends Controller
{
    public function index()
    {
        return view('smartcommunication::index');
    }

    public function tickets()
    {
        try {
            $complaints = \App\SmComplaint::where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'desc')
                ->get();
            return view('smartcommunication::tickets', compact('complaints'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function eventRsvp()
    {
        try {
            $events = \App\SmEvent::where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'desc')
                ->get();
            return view('smartcommunication::event_rsvp', compact('events'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function whatsappHub()
    {
        return view('smartcommunication::whatsapp_hub');
    }
}
