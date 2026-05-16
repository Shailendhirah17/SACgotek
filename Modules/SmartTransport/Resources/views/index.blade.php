@extends('backEnd.master')
@section('title') Smart Transport @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Smart Transport Hub</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="#">Smart Transport</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0"><div class="row">
    <div class="col-lg-4 col-md-6"><a href="{{route('stp.live_tracking')}}" class="d-block mb-20 text-center color-bg-1 p-3 text-white rounded"><h4 class="text-white"><i class="ti-location-pin"></i> Live GPS Tracking</h4></a></div>
    <div class="col-lg-4 col-md-6"><a href="{{route('stp.driver_metrics')}}" class="d-block mb-20 text-center color-bg-2 p-3 text-white rounded"><h4 class="text-white"><i class="ti-dashboard"></i> Driver Safety Metrics</h4></a></div>
    <div class="col-lg-4 col-md-6"><a href="{{route('stp.parent_view')}}" class="d-block text-center color-bg-3 p-3 text-white rounded"><h4 class="text-white"><i class="ti-eye"></i> Parent ETA View</h4></a></div>
</div></div></section>
@endsection
