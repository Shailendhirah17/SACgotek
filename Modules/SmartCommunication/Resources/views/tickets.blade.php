@extends('backEnd.master')
@section('title') Issue Ticketing @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Issue Ticketing System</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('scom.index')}}">Communication</a><a href="#">Tickets</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box">
        <h4>Complaint & Query Tickets (From <code>sm_complaints</code>)</h4><hr>
        @if(isset($complaints) && $complaints->count() > 0)
        <table class="table school-table-style">
            <thead><tr><th>#</th><th>Complainant</th><th>Type</th><th>Description</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>@foreach($complaints as $key => $c)
            <tr><td>{{$key+1}}</td><td>{{$c->name ?? 'N/A'}}</td><td>{{$c->complain_type ?? ''}}</td><td>{{Str::limit($c->description, 60)}}</td><td>{{dateConvert($c->date)}}</td>
                <td>@if($c->action_taken)<span class="badge badge-success">Resolved</span>@else<span class="badge badge-warning">Open</span>@endif</td></tr>
            @endforeach</tbody>
        </table>
        @else <p class="text-muted text-center">No complaints/tickets filed.</p> @endif
    </div>
</div></section>
@endsection
