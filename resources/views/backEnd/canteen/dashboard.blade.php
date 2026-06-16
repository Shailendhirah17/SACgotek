@extends('backEnd.master')
@section('title') Canteen Dashboard @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Canteen Dashboard</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Canteen</a>
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
                            <h4>Total Wallets</h4>
                            <h2 class="text-primary">{{ $totalWallets }}</h2>
                        </div>
                        <i class="fas fa-wallet fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Total Balance</h4>
                            <h2 class="text-success">${{ number_format($totalBalance, 2) }}</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Active Items</h4>
                            <h2 class="text-info">{{ $activeItems }}</h2>
                        </div>
                        <i class="fas fa-hamburger fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card bg-primary text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="text-white">Open POS</h4>
                            <a href="{{ route('canteen.pos') }}" class="btn btn-light mt-2 font-weight-bold">Launch POS</a>
                        </div>
                        <i class="fas fa-cash-register fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <h4 class="mb-20">Recent Sales</h4>
                    <table class="table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transactions</th>
                                <th>Total Revenue</th>
                                <th>Total Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                            <tr>
                                <td>{{ date('d M Y', strtotime($sale->sale_date)) }}</td>
                                <td>{{ $sale->total_transactions }}</td>
                                <td>${{ number_format($sale->total_revenue, 2) }}</td>
                                <td><span class="text-success">${{ number_format($sale->total_profit, 2) }}</span></td>
                            </tr>
                            @endforeach
                            @if($sales->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">No sales data found</td>
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
