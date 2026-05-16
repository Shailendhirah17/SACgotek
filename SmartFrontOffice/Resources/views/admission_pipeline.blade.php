@extends('backEnd.master')
@section('title') Admission Pipeline @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Admission Pipeline</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('sfo.index')}}">Front Office</a><a href="#">Admission</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box">
        <h4>Admission Queries (From <code>sm_admission_queries</code>)</h4>
        <p class="text-muted">Kanban-style admission workflow: Applied → Shortlisted → Interview → Admitted</p><hr>
        @if(isset($queries) && $queries->count() > 0)
        <table class="table school-table-style">
            <thead><tr><th>#</th><th>Name</th><th>Class</th><th>Phone</th><th>Date</th><th>Follow-ups</th><th>Stage</th></tr></thead>
            <tbody>@foreach($queries as $key => $q)
            <tr><td>{{$key+1}}</td><td>{{$q->name}}</td><td>{{$q->class_id ?? ''}}</td><td>{{$q->phone ?? ''}}</td><td>{{dateConvert($q->date)}}</td>
                <td><span class="badge badge-primary">{{$q->followUps->count() ?? 0}} follow-ups</span></td>
                <td>@if($q->student_id)<span class="badge badge-success">Admitted</span>@elseif($q->followUps->count() > 0)<span class="badge badge-info">In Progress</span>@else<span class="badge badge-warning">New Lead</span>@endif</td></tr>
            @endforeach</tbody>
        </table>
        @else <p class="text-muted text-center">No admission queries found.</p> @endif
    </div>
</div></section>
@endsection
