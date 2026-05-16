@extends('backEnd.master')
@section('title') Exam Portal (Pro) @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid"><div class="row justify-content-between"><h1>Advanced Exam Portal</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="#">Exam Portal (Pro)</a></div></div></div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0"><div class="row">
        <div class="col-lg-4 col-md-6"><a href="{{route('aep.ai_generator')}}" class="d-block mb-20 text-center color-bg-1 p-3 text-white rounded"><h4 class="text-white"><i class="ti-wand"></i> AI Question Generator</h4></a></div>
        <div class="col-lg-4 col-md-6"><a href="{{route('aep.result_heatmap')}}" class="d-block mb-20 text-center color-bg-2 p-3 text-white rounded"><h4 class="text-white"><i class="ti-bar-chart-alt"></i> Result Heatmap</h4></a></div>
        <div class="col-lg-4 col-md-6"><a href="{{route('aep.portfolio')}}" class="d-block text-center color-bg-3 p-3 text-white rounded"><h4 class="text-white"><i class="ti-folder"></i> Portfolio Assessment</h4></a></div>
    </div></div>
</section>
@endsection
