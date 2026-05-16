@extends('backEnd.master')
@section('title') Vendor Payments @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Vendor Payments</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Vendor &amp; Accounts</a>
                <a href="#">Vendor Payments</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Record Payment Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-money-check-alt mr-2 text-success"></i> Record Payment</h4>
                    <form action="{{ route('vendor.payment.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Vendor <span class="text-danger">*</span></label>
                            <select name="vendor_id" class="form-control" required>
                                <option value="">-- Select Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Purchase Order (optional)</label>
                            <select name="purchase_order_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach($purchase_orders as $po)
                                    <option value="{{ $po->id }}">PO #{{ $po->id }} — ₹{{ number_format($po->total_amount, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach(['Cash','Cheque','Bank Transfer','UPI','NEFT','RTGS'] as $m)
                                    <option>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Reference No.</label>
                            <input type="text" name="reference_no" class="form-control" placeholder="Cheque / Transaction No.">
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Optional remarks..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-save mr-1"></i> Record Payment
                        </button>
                    </form>
                </div>
            </div>

            {{-- Payment List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-success"></i> Payment History</h4>

                    {{-- Summary Cards --}}
                    <div class="row mb-20">
                        <div class="col-4">
                            <div class="card text-center" style="border-left:4px solid #4CAF50;">
                                <div class="card-body py-2">
                                    <small class="text-muted">Total Paid</small>
                                    <h5 class="mb-0 text-success">
                                        ₹{{ number_format($payments->sum('amount'), 2) }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card text-center" style="border-left:4px solid #2196F3;">
                                <div class="card-body py-2">
                                    <small class="text-muted">Transactions</small>
                                    <h5 class="mb-0 text-primary">{{ $payments->count() }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card text-center" style="border-left:4px solid #FF9800;">
                                <div class="card-body py-2">
                                    <small class="text-muted">Vendors</small>
                                    <h5 class="mb-0 text-warning">{{ $vendors->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="payTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Vendor</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Ref. No.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $i => $pay)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $pay->vendor->vendor_name ?? '—' }}</td>
                                    <td><strong class="text-success">₹{{ number_format($pay->amount, 2) }}</strong></td>
                                    <td>{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') : '—' }}</td>
                                    <td>
                                        @if($pay->payment_method)
                                            <span class="badge badge-info">{{ $pay->payment_method }}</span>
                                        @else —
                                        @endif
                                    </td>
                                    <td>{{ $pay->reference_no ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('vendor.payment.delete', $pay->id) }}"
                                           class="primary-btn small bg-danger border-0"
                                           onclick="return confirm('Delete this payment?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No payments recorded yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#payTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
