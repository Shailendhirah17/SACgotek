@extends('backEnd.master')
@section('title')
HR Portal (Admin)
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Executive HR Dashboard</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">HR Portal (Pro)</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-title">
                    <h3 class="mb-30">HR Operations Center</h3>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <a href="{{route('ahp.staff_view')}}" class="d-block mb-20 text-center color-bg-1 p-3 text-white rounded">
                            <h4 class="text-white"><i class="ti-id-badge"></i> 360 Staff View</h4>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{route('ahp.payroll')}}" class="d-block mb-20 text-center color-bg-2 p-3 text-white rounded">
                            <h4 class="text-white"><i class="ti-money"></i> Payroll Master</h4>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{route('ahp.attendance')}}" class="d-block text-center color-bg-3 p-3 text-white rounded">
                            <h4 class="text-white"><i class="ti-calendar"></i> Attendance Monitor</h4>
                        </a>
                    </div>
                </div>
                
                <div class="white-box mt-30 text-center">
                    <img src="{{asset('public/backEnd/img/client/hr-portal.jpg')}}" alt="HR Portals" style="width: 250px; opacity: 0.8;" onerror="this.style.display='none'">
                    <h5 class="mt-20">Advanced Analytics Available</h5>
                    <p>Track student satisfaction, appraisal progression, and cross-department budget expenditure.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
