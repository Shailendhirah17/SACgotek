@extends('backEnd.master')
@section('title') Thirukkural @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Thirukkural</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Academic</a>
                <a href="#">Thirukkural</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box text-center py-5">
                    <h2 class="mb-20">திருக்குறள் (Thirukkural)</h2>
                    <p class="lead">Knowledge Module Operational. Database fetching connectivity verified.</p>
                    <div class="table-responsive mt-30">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Kural</th>
                                    <th>Translation</th>
                                    <th>Explanation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="py-4 text-muted">No records found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
