@extends('backEnd.master')
@section('title')
Attendance Monitor
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Staff Attendance Monitor</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="{{route('ahp.index')}}">HR Portal</a>
                <a href="#">Attendance Monitor</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="white-box text-center p-4">
                    <div class="color-bg-1 text-white rounded p-3 mb-20">
                        <h2 class="text-white"><i class="ti-timer"></i></h2>
                    </div>
                    <h4>Real-Time Check-in</h4>
                    <p class="text-muted">Biometric & Mobile GPS-based check-in mapped from <code>sm_staff_attendences</code>.</p>
                    <a href="{{ url('staff-attendance') }}" class="primary-btn small fix-gr-bg mt-10">
                        <i class="ti-arrow-right"></i> Open Core Attendance
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="white-box text-center p-4">
                    <div class="color-bg-2 text-white rounded p-3 mb-20">
                        <h2 class="text-white"><i class="ti-alarm-clock"></i></h2>
                    </div>
                    <h4>Late Arrival Alerts</h4>
                    <p class="text-muted">Automated notification engine: flags staff arriving more than 15 minutes past scheduled time.</p>
                    <span class="badge badge-warning">Coming Soon</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="white-box text-center p-4">
                    <div class="color-bg-3 text-white rounded p-3 mb-20">
                        <h2 class="text-white"><i class="ti-bar-chart"></i></h2>
                    </div>
                    <h4>Monthly Reports</h4>
                    <p class="text-muted">Quarterly attendance analytics with exportable CSV/PDF summaries.</p>
                    <a href="{{ url('staff-attendance-report') }}" class="primary-btn small tr-bg mt-10">
                        <i class="ti-arrow-right"></i> Open Core Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
