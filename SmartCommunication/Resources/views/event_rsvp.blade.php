@extends('backEnd.master')
@section('title') Event RSVP @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Event RSVP Management</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('scom.index')}}">Communication</a><a href="#">Event RSVP</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box">
        <h4>School Events (From <code>sm_events</code>)</h4><hr>
        @if(isset($events) && $events->count() > 0)
        <div class="row">@foreach($events as $e)
        <div class="col-lg-4 col-md-6 mb-20">
            <div class="white-box student-details rtl-border">
                <h4>{{$e->event_title}}</h4>
                <p><strong>Location:</strong> {{$e->event_location ?? 'TBD'}}</p>
                <p><strong>Date:</strong> {{dateConvert($e->from_date)}} — {{dateConvert($e->to_date)}}</p>
                <hr>
                <div class="row text-center">
                    <div class="col-6"><button class="primary-btn small fix-gr-bg w-100">✓ RSVP Yes</button></div>
                    <div class="col-6"><button class="primary-btn small tr-bg w-100">✗ Decline</button></div>
                </div>
            </div>
        </div>
        @endforeach</div>
        @else <p class="text-muted text-center">No upcoming events.</p> @endif
    </div>
</div></section>
@endsection
