@extends('backEnd.master')
@section('title')
My HR Hub
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>My HR Hub (Self-Service)</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">My HR Hub</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Left Column: Staff Profile --}}
            <div class="col-lg-4">
                <div class="white-box text-center mb-30">
                    @if(isset($staff))
                    <img src="{{ file_exists($staff->staff_photo) ? asset($staff->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" class="rounded-circle mb-3" style="width: 130px; height: 130px; object-fit: cover;">
                    <h3>{{$staff->full_name ?? 'Staff'}}</h3>
                    <p class="mb-0">{{$staff->designations->title ?? 'N/A'}} | {{$staff->departments->name ?? 'N/A'}}</p>
                    <p>Staff ID: {{$staff->staff_no ?? 'N/A'}}</p>
                    @else
                    <h4 class="text-danger">Staff profile not linked.</h4>
                    @endif
                </div>
            </div>

            {{-- Right Column: Tabs --}}
            <div class="col-lg-8">
                <div class="white-box mb-30">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#leave_tab">Leave Requests</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#payslip_tab">My Pay Slips</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kpi_tab">Performance & KPIs</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-30">
                        {{-- Leave Requests Tab --}}
                        <div id="leave_tab" class="container tab-pane active"><br>
                            @if(isset($leave_requests) && $leave_requests->count() > 0)
                            <table class="table school-table-style">
                                <thead>
                                    <tr><th>Type</th><th>From</th><th>To</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($leave_requests as $lr)
                                    <tr>
                                        <td>{{$lr->leaveType->type ?? 'N/A'}}</td>
                                        <td>{{dateConvert($lr->leave_from)}}</td>
                                        <td>{{dateConvert($lr->leave_to)}}</td>
                                        <td>
                                            @if($lr->approve_status == 'A')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($lr->approve_status == 'C')
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p class="text-muted">No leave requests found.</p>
                            @endif
                            <a href="{{ url('leave-apply') }}" class="primary-btn small fix-gr-bg mt-10">
                                <i class="ti-plus"></i> Apply for Leave (Core)
                            </a>
                        </div>

                        {{-- Pay Slips Tab --}}
                        <div id="payslip_tab" class="container tab-pane fade"><br>
                            @if(isset($payroll_slips) && $payroll_slips->count() > 0)
                            <table class="table school-table-style">
                                <thead>
                                    <tr><th>Month</th><th>Year</th><th>Basic</th><th>Net</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($payroll_slips as $slip)
                                    <tr>
                                        <td>{{$slip->payroll_month}}</td>
                                        <td>{{$slip->payroll_year}}</td>
                                        <td>{{$slip->basic_salary ?? '0.00'}}</td>
                                        <td><strong>{{$slip->net_salary ?? '0.00'}}</strong></td>
                                        <td><a href="#" class="primary-btn small tr-bg"><i class="ti-download"></i> PDF</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p class="text-muted">No payroll slips available yet.</p>
                            @endif
                        </div>

                        {{-- KPI Tab --}}
                        <div id="kpi_tab" class="container tab-pane fade"><br>
                            <div class="text-center">
                                <h4>Performance Management</h4>
                                <p>360-degree feedback forms, KPI tracking, and development plans will appear here once configured by your HR admin.</p>
                                <span class="badge badge-info">Module Activating Soon</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
