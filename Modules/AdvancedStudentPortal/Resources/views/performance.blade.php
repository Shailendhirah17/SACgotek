@extends('backEnd.master')
@section('title')
Performance Tracking
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Performance Tracking (Overview)</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">Advanced Portal</a>
                <a href="#">Performance</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <h3>Performance Tracking Module Integrations</h3>
                    <p>This module reads directly from <code>sm_marks_registers</code> and <code>sm_attendances</code> tables.</p>
                    <hr>
                    <div class="row mt-30">
                        <div class="col-lg-4 col-md-6">
                            <a href="#" class="d-block mb-20 text-center color-bg-1 p-3 text-white rounded">
                                <h4 class="text-white"><i class="ti-bar-chart"></i> Realtime Class Metrics</h4>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="#" class="d-block mb-20 text-center color-bg-2 p-3 text-white rounded">
                                <h4 class="text-white"><i class="ti-check-box"></i> Attendance Forecasting</h4>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="#" class="d-block text-center color-bg-3 p-3 text-white rounded">
                                <h4 class="text-white"><i class="ti-cup"></i> Competency Badges</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
