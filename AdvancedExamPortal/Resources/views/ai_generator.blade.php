@extends('backEnd.master')
@section('title') AI Question Generator @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid"><div class="row justify-content-between"><h1>AI Question Generator</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('aep.index')}}">Exam Portal</a><a href="#">AI Generator</a></div></div></div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="white-box mb-30">
                    <h4>Generation Settings</h4><hr>
                    <div class="mb-20">
                        <label>Subject</label>
                        <select class="niceSelect w-100 bb form-control">
                            <option>Select Subject</option>
                            @if(isset($subjects))@foreach($subjects as $s)<option>{{$s->subject->subject_name ?? ''}}</option>@endforeach @endif
                        </select>
                    </div>
                    <div class="mb-20">
                        <label>Bloom's Taxonomy Level</label>
                        <select class="niceSelect w-100 bb form-control">
                            <option>Remember</option><option>Understand</option><option>Apply</option><option>Analyze</option><option>Evaluate</option><option>Create</option>
                        </select>
                    </div>
                    <div class="mb-20">
                        <label>Question Type</label>
                        <select class="niceSelect w-100 bb form-control">
                            <option>MCQ</option><option>Fill in the Blank</option><option>Short Answer</option><option>Essay</option>
                        </select>
                    </div>
                    <div class="mb-20">
                        <label>Number of Questions</label>
                        <input type="number" class="primary-input form-control" value="10">
                    </div>
                    <button class="primary-btn small fix-gr-bg w-100"><i class="ti-wand"></i> Generate Questions</button>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="white-box mb-30">
                    <h4>Existing Question Bank (Last 50)</h4><hr>
                    @if(isset($question_banks) && $question_banks->count() > 0)
                    <table class="table school-table-style">
                        <thead><tr><th>#</th><th>Question</th><th>Type</th><th>Level</th></tr></thead>
                        <tbody>
                            @foreach($question_banks as $key => $qb)
                            <tr><td>{{$key+1}}</td><td>{{Str::limit($qb->question, 80)}}</td><td>{{$qb->type ?? 'MCQ'}}</td><td>{{$qb->questionLevel->title ?? 'N/A'}}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else <p class="text-muted">No questions in the bank yet.</p> @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
