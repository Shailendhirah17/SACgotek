@extends('backEnd.master')
@section('title')
Behavior & Badges
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Behavior & Badges</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">Advanced Portal</a>
                <a href="#">Behavior & Badges</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box mt-30 text-center">
                    <img src="{{asset('public/backEnd/img/client/behavior.jpg')}}" alt="Behavior Modules" style="width: 250px; opacity: 0.8;" onerror="this.style.display='none'">
                    <h3 class="mt-20">Behavior Records Core Integration</h3>
                    <p>This module uses the <strong>InfixEdu Incident Management system</strong> (the <code>incidents</code> and <code>assign_incidents</code> tables) to issue positive badges and disciplinary actions.</p>
                    <a href="{{ route('behaviour_records.incident') }}" class="primary-btn small fix-gr-bg mt-20">
                        <i class="ti-arrow-right pr-2"></i> Go to Core Incident Manager
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
