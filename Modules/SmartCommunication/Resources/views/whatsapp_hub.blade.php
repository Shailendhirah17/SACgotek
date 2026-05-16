@extends('backEnd.master')
@section('title') WhatsApp Hub @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb"><div class="container-fluid"><div class="row justify-content-between"><h1>WhatsApp Integration Hub</h1><div class="bc-pages"><a href="{{route('dashboard')}}">Dashboard</a><a href="{{route('scom.index')}}">Communication</a><a href="#">WhatsApp</a></div></div></div></section>
<section class="admin-visitor-area up_admin_visitor"><div class="container-fluid p-0">
    <div class="white-box text-center">
        <h3><i class="ti-comment-alt" style="font-size:60px; color:#25D366;"></i></h3>
        <h4 class="mt-20">WhatsApp Business API Integration</h4>
        <p>Send automated notifications to parents via WhatsApp: attendance alerts, fee reminders, exam results, and event announcements.</p>
        <hr>
        <div class="row mt-20">
            <div class="col-lg-3"><div class="p-3 color-bg-1 text-white rounded"><h5 class="text-white">Attendance Alerts</h5></div></div>
            <div class="col-lg-3"><div class="p-3 color-bg-2 text-white rounded"><h5 class="text-white">Fee Reminders</h5></div></div>
            <div class="col-lg-3"><div class="p-3 color-bg-3 text-white rounded"><h5 class="text-white">Exam Results</h5></div></div>
            <div class="col-lg-3"><div class="p-3" style="background:#25D366; border-radius:8px;"><h5 class="text-white">Event Notices</h5></div></div>
        </div>
        <p class="mt-30 text-muted">Configure your WhatsApp Business API credentials in Settings → Communication to activate.</p>
    </div>
</div></section>
@endsection
