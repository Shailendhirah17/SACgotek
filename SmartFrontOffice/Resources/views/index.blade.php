@extends('backEnd.master')
@section('title') Smart Front Office @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Smart Front Office</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="#">Front Office</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0"><div class="row">
    <div class="col-lg-4 col-md-6"><a href="{{route('sfo.admission_pipeline')}}" class="d-block mb-20 text-center color-bg-1 p-3 text-white rounded"><h4 class="text-white"><i class="ti-user"></i> Admission Pipeline</h4></a></div>
    <div class="col-lg-4 col-md-6"><a href="{{route('sfo.visitor_passes')}}" class="d-block mb-20 text-center color-bg-2 p-3 text-white rounded"><h4 class="text-white"><i class="ti-id-badge"></i> Visitor Passes</h4></a></div>
    <div class="col-lg-4 col-md-6"><a href="{{route('sfo.leads')}}" class="d-block text-center color-bg-3 p-3 text-white rounded"><h4 class="text-white"><i class="ti-target"></i> Lead Nurturing</h4></a></div>
</div></div></section>
@endsection
