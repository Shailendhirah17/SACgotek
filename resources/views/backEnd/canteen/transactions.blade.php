@extends('backEnd.master')
@section('title') Canteen Transactions @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Transaction History</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Canteen</a>
                <a href="#">Transactions</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <table class="table school-table-style" id="transactionTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Student</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Item Details</th>
                                <th>Balance After</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $txn)
                            <tr>
                                <td>{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $txn->wallet->student->full_name ?? 'Unknown' }}</td>
                                <td>
                                    @if($txn->type == 'purchase')
                                        <span class="badge badge-danger">Purchase</span>
                                    @else
                                        <span class="badge badge-success">Recharge</span>
                                    @endif
                                </td>
                                <td><strong>${{ number_format($txn->amount, 2) }}</strong></td>
                                <td>
                                    @if($txn->item_id)
                                        {{ $txn->quantity }}x {{ $txn->item->item_name ?? 'Item' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>${{ number_format($txn->balance_after, 2) }}</td>
                                <td>{{ ucfirst($txn->payment_method) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#transactionTable').DataTable({ responsive: true, order: [[0, 'desc']] });
});
</script>
@endpush
