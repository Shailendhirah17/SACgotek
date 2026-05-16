@extends('backEnd.master')
@section('title') Lead Nurturing @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Lead Nurturing Tracker</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('sfo.index')}}">Front Office</a><a href="#">Leads</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box">
        <h4>Unconverted Leads (From <code>sm_admission_queries</code> where student_id = NULL)</h4><hr>
        @if(isset($leads) && $leads->count() > 0)
        <table class="table school-table-style">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>@foreach($leads as $key => $l)
            <tr><td>{{$key+1}}</td><td>{{$l->name}}</td><td>{{$l->email ?? 'N/A'}}</td><td>{{$l->phone ?? 'N/A'}}</td><td>{{dateConvert($l->date)}}</td>
                <td><a href="#" class="primary-btn small fix-gr-bg"><i class="ti-arrow-right"></i> Follow Up</a></td></tr>
            @endforeach</tbody>
        </table>
        @else <p class="text-muted text-center">All leads have been converted or no leads exist.</p> @endif
    </div>
</div></section>
@endsection
