@extends('backEnd.master')
@section('title') Canteen Wallets @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Student Wallets</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Canteen</a>
                <a href="#">Wallets</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Add / Update Wallet</h4>
                    <form action="{{ route('canteen.wallets.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="student_id" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->admission_no }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>RFID Card UID</label>
                            <input type="text" name="rfid_card_uid" class="form-control" placeholder="Tap card or enter UID">
                        </div>
                        <div class="form-group">
                            <label>Daily Limit ($) <span class="text-danger">*</span></label>
                            <input type="number" name="daily_limit" class="form-control" value="200" required>
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Save Wallet</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Wallet List</h4>
                    <table class="table school-table-style" id="walletTable">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Admission No</th>
                                <th>RFID UID</th>
                                <th>Balance</th>
                                <th>Daily Limit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wallets as $wallet)
                            <tr>
                                <td>{{ $wallet->student->full_name ?? 'N/A' }}</td>
                                <td>{{ $wallet->student->admission_no ?? 'N/A' }}</td>
                                <td>{{ $wallet->rfid_card_uid ?? 'Not Assigned' }}</td>
                                <td>
                                    <strong class="{{ $wallet->balance > 0 ? 'text-success' : 'text-danger' }}">
                                        ${{ number_format($wallet->balance, 2) }}
                                    </strong>
                                </td>
                                <td>${{ number_format($wallet->daily_limit, 2) }}</td>
                                <td>
                                    <button class="primary-btn small bg-success text-white border-0" data-toggle="modal" data-target="#rechargeModal{{ $wallet->id }}">
                                        Recharge
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Recharge Modal -->
                            <div class="modal fade" id="rechargeModal{{ $wallet->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Recharge Wallet: {{ $wallet->student->full_name ?? '' }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <form action="{{ route('canteen.wallets.recharge') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="wallet_id" value="{{ $wallet->id }}">
                                            <div class="modal-body">
                                                <div class="form-group text-left">
                                                    <label>Amount ($)</label>
                                                    <input type="number" name="amount" class="form-control" min="1" required>
                                                </div>
                                                <div class="form-group text-left">
                                                    <label>Payment Method</label>
                                                    <select name="payment_method" class="form-control">
                                                        <option value="cash">Cash</option>
                                                        <option value="upi">UPI / Online</option>
                                                        <option value="bank">Bank Transfer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="primary-btn fix-gr-bg">Recharge</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
    $('#walletTable').DataTable({ responsive: true });
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
