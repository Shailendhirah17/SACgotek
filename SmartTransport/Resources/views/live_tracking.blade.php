@extends('backEnd.master')
@section('title') Live GPS Tracking @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Live GPS Tracking</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('stp.index')}}">Transport</a><a href="#">Live Tracking</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box">
        <h4>Fleet Vehicles (From <code>sm_vehicles</code>)</h4><hr>
        @if(isset($vehicles) && $vehicles->count() > 0)
        <table class="table school-table-style">
            <thead><tr><th>Vehicle No</th><th>Model</th><th>Driver</th><th>Year</th><th>Status</th></tr></thead>
            <tbody>@foreach($vehicles as $v)
            <tr><td>{{$v->vehicle_no}}</td><td>{{$v->vehicle_model}}</td><td>{{$v->driver_name ?? 'N/A'}}</td><td>{{$v->made_year ?? ''}}</td>
                <td><span class="badge badge-success">Active</span></td></tr>
            @endforeach</tbody>
        </table>
        @else <p class="text-muted">No vehicles registered.</p> @endif
        <hr>
        <h4>Routes (From <code>sm_routes</code>)</h4>
        @if(isset($routes) && $routes->count() > 0)
        <table class="table school-table-style">
            <thead><tr><th>Title</th><th>Fare</th></tr></thead>
            <tbody>@foreach($routes as $r)<tr><td>{{$r->title}}</td><td>{{$r->far ?? '0.00'}}</td></tr>@endforeach</tbody>
        </table>
        @else <p class="text-muted">No routes defined.</p> @endif
        <div class="text-center mt-30 p-4" style="background:#f0f7ff; border-radius:8px;">
            <h3><i class="ti-location-pin" style="font-size:50px; color:#415094;"></i></h3>
            <p>GPS hardware integration point — connect your fleet tracking API here for real-time map overlay.</p>
        </div>
    </div>
</div></section>
@endsection
