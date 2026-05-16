@extends('backEnd.master')
@section('title') Driver Metrics @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Driver Safety Metrics</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('stp.index')}}">Transport</a><a href="#">Driver Metrics</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box">
        <h4>Driver Performance Dashboard</h4><p class="text-muted">Safety scores, harsh braking events, and on-time delivery rates.</p><hr>
        @if(isset($vehicles) && $vehicles->count() > 0)
        <div class="row">@foreach($vehicles as $v)
        <div class="col-lg-4 col-md-6 mb-20">
            <div class="white-box student-details rtl-border text-center">
                <h4>{{$v->driver_name ?? 'Driver N/A'}}</h4>
                <p class="mb-0">Vehicle: {{$v->vehicle_no}}</p>
                <hr>
                <div class="row">
                    <div class="col-6"><h5>Safety Score</h5><h2 class="text-success">92%</h2></div>
                    <div class="col-6"><h5>On-Time %</h5><h2 class="text-primary">87%</h2></div>
                </div>
            </div>
        </div>
        @endforeach</div>
        @else <p class="text-danger text-center">No vehicles with drivers found.</p> @endif
    </div>
</div></section>
@endsection
