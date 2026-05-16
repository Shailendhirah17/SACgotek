@extends('backEnd.master')
@section('title') Result Heatmap @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid"><div class="row justify-content-between"><h1>Result Heatmap Analytics</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('aep.index')}}">Exam Portal</a><a href="#">Heatmap</a></div></div></div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box">
            <h4>Class & Subject Performance Heatmap</h4>
            <p class="text-muted">Reads from <code>sm_marks_registers</code> and <code>sm_exam_marks_registers</code> to generate performance heatmaps.</p><hr>
            <div class="row mb-20">
                <div class="col-lg-4"><select class="niceSelect w-100 bb form-control"><option>Select Class</option>@if(isset($classes))@foreach($classes as $c)<option>{{$c->class_name}}</option>@endforeach @endif</select></div>
                <div class="col-lg-4"><select class="niceSelect w-100 bb form-control"><option>Select Exam</option>@if(isset($exams))@foreach($exams as $e)<option>{{$e->title}}</option>@endforeach @endif</select></div>
                <div class="col-lg-4"><button class="primary-btn small fix-gr-bg w-100 mt-0"><i class="ti-bar-chart"></i> Generate Heatmap</button></div>
            </div>
            <div class="text-center mt-40 p-5" style="background:#f8f9fa; border-radius:8px;">
                <h3 style="color:#ccc;"><i class="ti-layout-grid3-alt" style="font-size:60px;"></i></h3>
                <p class="text-muted">Select a class and exam to generate the performance heatmap.</p>
            </div>
        </div>
    </div>
</section>
@endsection
