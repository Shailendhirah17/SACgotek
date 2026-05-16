@extends('backEnd.master')
@section('title') Parent ETA View @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Parent Bus ETA View</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('stp.index')}}">Transport</a><a href="#">Parent ETA</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box text-center">
        <h3><i class="ti-car" style="font-size:60px; color:#415094;"></i></h3>
        <h4 class="mt-20">Real-Time Bus Location</h4>
        <p>Parents can track their child's bus in real-time with estimated arrival times.</p>
        <hr>
        @if(isset($routes) && $routes->count() > 0)
        <div class="row mt-20">@foreach($routes as $r)
        <div class="col-lg-4 col-md-6 mb-20">
            <div class="p-3 color-bg-1 text-white rounded"><h5 class="text-white">{{$r->title}}</h5><p class="mb-0">Fare: {{$r->far ?? 'N/A'}}</p><small>ETA: ~15 min</small></div>
        </div>
        @endforeach</div>
        @else <p class="text-muted">No routes configured for parent tracking.</p> @endif
    </div>
</div></section>
@endsection
