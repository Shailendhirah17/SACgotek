@extends('backEnd.master')
@section('title') Vendor Dashboard @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Vendor Dashboard</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Vendor</a>
                <a href="#">Dashboard</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row mb-40">
            <!-- Summary Cards -->
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Total Vendors</h4>
                            <h2 class="text-primary">{{ $totalVendors }}</h2>
                        </div>
                        <i class="fas fa-truck-loading fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Active Agreements</h4>
                            <h2 class="text-success">{{ $activeAgreements }}</h2>
                        </div>
                        <i class="fas fa-file-contract fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Pending POs</h4>
                            <h2 class="text-warning">{{ $pendingPOs }}</h2>
                        </div>
                        <i class="fas fa-file-invoice fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card bg-primary text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="text-white">Quick Links</h4>
                            <a href="{{ route('vendor.index') }}" class="btn btn-light btn-sm mt-2 font-weight-bold">Vendor List</a>
                        </div>
                        <i class="fas fa-link fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <h4 class="mb-20">Recent Payments</h4>
                    <table class="table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td>{{ date('d M Y', strtotime($payment->payment_date)) }}</td>
                                <td>{{ $payment->vendor->vendor_name ?? 'N/A' }}</td>
                                <td><span class="text-success font-weight-bold">${{ number_format($payment->amount, 2) }}</span></td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                            </tr>
                            @endforeach
                            @if($recentPayments->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">No payments found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.dashboard-card { padding: 25px; border-radius: 10px; transition: all 0.3s ease; }
.dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
</style>
@endsection
