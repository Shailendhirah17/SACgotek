@extends('backEnd.master')
@section('title')
Advanced Student Portal
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Advanced Student Portal Dashboard</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">Advanced Portal</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-title">
                    <h3 class="mb-30">Welcome to Advanced Portal</h3>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <a href="{{route('asp.student_view')}}" class="d-block mb-20 text-center color-bg-1 p-3 text-white rounded">
                            <h4 class="text-white"><i class="ti-user"></i> 360 Student View</h4>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{route('asp.performance')}}" class="d-block mb-20 text-center color-bg-2 p-3 text-white rounded">
                            <h4 class="text-white"><i class="ti-bar-chart"></i> Performance Tracking</h4>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{route('asp.behavior')}}" class="d-block text-center color-bg-3 p-3 text-white rounded">
                            <h4 class="text-white"><i class="ti-cup"></i> Behavior & Badges</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
