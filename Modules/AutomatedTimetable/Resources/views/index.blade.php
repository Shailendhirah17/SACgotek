@extends('backEnd.master')
@section('title')
Automated Timetable Dashboard
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Automated Timetable</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">Dashboard</a>
                <a href="#">Automated Timetable</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-30">
                <div class="white-box text-center">
                    <span class="ti-calendar" style="font-size: 50px; color: #415094;"></span>
                    <h3 class="mt-20">Grid Editor</h3>
                    <p>Auto-generate schedules via CSP engine, force-assign teachers, and swap periods instantly.</p>
                    <a href="{{ route('automatedtimetable.editor') }}" class="primary-btn small fix-gr-bg mt-10">Open Editor</a>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6 mb-30">
                <div class="white-box text-center">
                    <span class="ti-user" style="font-size: 50px; color: #415094;"></span>
                    <h3 class="mt-20">Teacher Substitutions</h3>
                    <p>Manage absent teachers and dynamically re-allocate free periods to available substitute staff.</p>
                    <a href="{{ route('automatedtimetable.substitutes') }}" class="primary-btn small fix-gr-bg mt-10">Manage Subs</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
