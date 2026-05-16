<?php

namespace Modules\SmartTransport\Http\Controllers;

use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SmartTransportController extends Controller
{
    public function index()
    {
        return view('smarttransport::index');
    }

    public function liveTracking()
    {
        try {
            $vehicles = \App\SmVehicle::where('school_id', auth()->user()->school_id)->get();
            $routes = \App\SmRoute::where('school_id', auth()->user()->school_id)->get();
            return view('smarttransport::live_tracking', compact('vehicles', 'routes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function driverMetrics()
    {
        try {
            $vehicles = \App\SmVehicle::where('school_id', auth()->user()->school_id)->get();
            return view('smarttransport::driver_metrics', compact('vehicles'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function parentView()
    {
        try {
            $routes = \App\SmRoute::where('school_id', auth()->user()->school_id)->get();
            return view('smarttransport::parent_view', compact('routes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
