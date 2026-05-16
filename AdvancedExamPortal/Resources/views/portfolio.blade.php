@extends('backEnd.master')
@section('title') Portfolio Assessment @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid"><div class="row justify-content-between"><h1>Portfolio Assessment</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('aep.index')}}">Exam Portal</a><a href="#">Portfolio</a></div></div></div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box text-center">
            <h3>Portfolio-Based Assessment System</h3>
            <p>Rubric-based evaluations, project submissions, and skill badge tracking.</p>
            <div class="row mt-30">
                <div class="col-lg-4"><div class="p-3 color-bg-1 text-white rounded"><h5 class="text-white">Project Submissions</h5><p class="mb-0">Upload & grade student projects</p></div></div>
                <div class="col-lg-4"><div class="p-3 color-bg-2 text-white rounded"><h5 class="text-white">Rubric Builder</h5><p class="mb-0">Create assessment rubrics</p></div></div>
                <div class="col-lg-4"><div class="p-3 color-bg-3 text-white rounded"><h5 class="text-white">Skill Badges</h5><p class="mb-0">Award competency badges</p></div></div>
            </div>
        </div>
    </div>
</section>
@endsection
