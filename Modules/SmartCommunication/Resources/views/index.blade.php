@extends('backEnd.master')
@section('title') Smart Communication @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>Smart Communication Hub</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="#">Communication</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0"><div class="row">
    <div class="col-lg-4 col-md-6"><a href="{{route('scom.tickets')}}" class="d-block mb-20 text-center color-bg-1 p-3 text-white rounded"><h4 class="text-white"><i class="ti-ticket"></i> Issue Ticketing</h4></a></div>
    <div class="col-lg-4 col-md-6"><a href="{{route('scom.event_rsvp')}}" class="d-block mb-20 text-center color-bg-2 p-3 text-white rounded"><h4 class="text-white"><i class="ti-calendar"></i> Event RSVP</h4></a></div>
    <div class="col-lg-4 col-md-6"><a href="{{route('scom.whatsapp_hub')}}" class="d-block text-center color-bg-3 p-3 text-white rounded"><h4 class="text-white"><i class="ti-comment-alt"></i> WhatsApp Hub</h4></a></div>
</div></div></section>
@endsection
