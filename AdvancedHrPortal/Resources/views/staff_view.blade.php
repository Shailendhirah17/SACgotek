@extends('backEnd.master')
@section('title')
360 Staff View
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>360 Staff View</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">HR Portal</a>
                <a href="#">360 Staff View</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($staffs) && count($staffs) > 0)
        <div class="row">
            @foreach($staffs as $staff)
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="student-meta-box">
                    <div class="white-box student-details rtl-border">
                        <div class="student-meta-top text-center">
                            <img src="{{ file_exists($staff->staff_photo) ? asset($staff->staff_photo) : asset('uploads/staff/demo/staff.jpg') }}" alt="" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            <h3 class="mt-20"><strong>{{$staff->full_name}}</strong></h3>
                            <p class="mb-0">{{$staff->designations->title ?? 'N/A'}} | {{$staff->departments->name ?? 'N/A'}}</p>
                            <p>Staff ID: {{$staff->staff_no}}</p>
                            <span class="badge badge-success">Active</span>
                        </div>
                        <hr>
                        <div class="student-meta-bottom">
                            <h5 class="mb-10"><strong>Bio & Contact</strong></h5>
                            <ul class="list-unstyled">
                                <li><strong>Phone:</strong> {{$staff->mobile ?? 'N/A'}}</li>
                                <li><strong>Email:</strong> {{$staff->email ?? 'N/A'}}</li>
                                <li><strong>Gender:</strong> {{$staff->genders->base_setup_name ?? 'N/A'}}</li>
                                <li><strong>Qualification:</strong> {{$staff->qualification ?? 'N/A'}}</li>
                            </ul>
                            
                            <hr>
                            <div class="row text-center mt-20">
                                <div class="col-6">
                                    <a href="#" class="primary-btn small fix-gr-bg w-100">
                                        <span class="ti-eye"></span> Appraisals
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="primary-btn small tr-bg w-100">
                                        <span class="ti-folder"></span> Incident Log
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="white-box">
                    <h3 class="text-center text-danger">No active staff found in this school!</h3>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
