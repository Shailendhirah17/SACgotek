@extends('backEnd.master')
@section('title') Visitor Passes @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Visitor Pass Management</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('sfo.index')}}">Front Office</a><a href="#">Visitor Passes</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box">
        <h4>Visitor Log (From <code>sm_visitors</code>)</h4><hr>
        @if(isset($visitors) && $visitors->count() > 0)
        <table class="table school-table-style">
            <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Purpose</th><th>Check-in</th><th>Check-out</th><th>Pass</th></tr></thead>
            <tbody>@foreach($visitors as $key => $v)
            <tr><td>{{$key+1}}</td><td>{{$v->name}}</td><td>{{$v->phone ?? 'N/A'}}</td><td>{{$v->purpose ?? ''}}</td>
                <td>{{$v->check_in ?? dateConvert($v->date)}}</td><td>{{$v->check_out ?? '—'}}</td>
                <td><button class="primary-btn small tr-bg"><i class="ti-printer"></i> Print</button></td></tr>
            @endforeach</tbody>
        </table>
        @else <p class="text-muted text-center">No visitor records found.</p> @endif
    </div>
</div></section>
@endsection
