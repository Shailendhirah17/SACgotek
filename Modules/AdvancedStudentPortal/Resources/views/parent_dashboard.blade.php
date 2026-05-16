@extends('backEnd.master')
@section('title')
Parent Dashboard (Child View)
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Parent Dashboard</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">Advanced Portal</a>
                <a href="#">Parent Dashboard</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-title">
                    <h3 class="mb-30">Welcome, {{$parent->fathers_name ?? $parent->guardians_name ?? 'Parent'}}</h3>
                </div>

                @if($children && $children->count() > 0)
                    @foreach($children as $child)
                    <div class="white-box mb-30">
                        <div class="row">
                            <div class="col-lg-3 text-center border-right">
                                <img src="{{ file_exists($child->student_photo) ? asset($child->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                                <h4>{{$child->full_name}}</h4>
                                <p>Class: {{$child->class->class_name ?? ''}} ({{$child->section->section_name ?? ''}})<br>
                                Roll: {{$child->roll_no}}</p>
                                <a href="#" class="primary-btn small fix-gr-bg mt-10"><i class="ti-comments"></i> Message Teacher</a>
                            </div>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 mb-20">
                                        <div class="p-3 color-bg-1 text-white rounded text-center">
                                            <h5>Marks Updated</h5>
                                            <h2>{{$child->marks->count() ?? 0}}</h2>
                                            <small>Past 24 hours synced</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-20">
                                        <div class="p-3 color-bg-2 text-white rounded text-center">
                                            <h5>Attendance</h5>
                                            <h2>{{$child->attendances->where('attendance_type', 'P')->count() ?? 0}}</h2>
                                            <small>Days Present</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-20">
                                        <div class="p-3 color-bg-3 text-white rounded text-center">
                                            <h5>Live Bus Tracking</h5>
                                            <h2><i class="ti-location-arrow"></i> Active</h2>
                                            <small>Route #{{$child->route_list_id ?? 'N/A'}}</small>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5>Recent Notices & Homework</h5>
                                <p class="text-muted">No new homework assigned today.</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="white-box">
                        <p class="text-danger text-center">No children records linked to your profile.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
