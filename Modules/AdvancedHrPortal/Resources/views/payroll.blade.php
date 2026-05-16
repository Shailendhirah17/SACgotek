@extends('backEnd.master')
@section('title')
Payroll Master
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Payroll Master</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="{{route('ahp.index')}}">HR Portal</a>
                <a href="#">Payroll Master</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row mb-20">
                        <div class="col-lg-6">
                            <h3>Generated Payroll Slips</h3>
                            <p class="text-muted">Sourced from <code>sm_hr_payroll_generates</code>. Includes TDS, PF, ESI calculations.</p>
                        </div>
                        <div class="col-lg-6 text-right">
                            <a href="{{ url('payroll') }}" class="primary-btn small fix-gr-bg">
                                <i class="ti-plus"></i> Generate New Payroll (Core)
                            </a>
                        </div>
                    </div>

                    @if(isset($payrolls) && $payrolls->count() > 0)
                    <table class="table school-table-style w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Staff Name</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Basic Salary</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $key => $slip)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$slip->staffDetails->full_name ?? 'N/A'}}</td>
                                <td>{{$slip->payroll_month ?? ''}}</td>
                                <td>{{$slip->payroll_year ?? ''}}</td>
                                <td>{{$slip->basic_salary ?? '0.00'}}</td>
                                <td><strong>{{$slip->net_salary ?? '0.00'}}</strong></td>
                                <td>
                                    @if($slip->payroll_status == 'G')
                                        <span class="badge badge-success">Generated</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="primary-btn small tr-bg"><i class="ti-printer"></i> Print Slip</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center mt-30">
                        <h4 class="text-danger">No payroll slips generated yet.</h4>
                        <p>Use the core Payroll module to generate salary slips first.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
