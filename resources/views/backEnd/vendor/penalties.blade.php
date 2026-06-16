@extends('backEnd.master')
@section('title') Vendor Penalties @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Penalties & Actions</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Vendor</a>
                <a href="#">Penalties</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Issue Penalty</h4>
                    <form action="{{ route('vendor.penalty.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Vendor <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="vendor_id" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Penalty Type</label>
                            <select name="penalty_type" class="form-control">
                                <option value="late_delivery">Late Delivery</option>
                                <option value="quality_issue">Quality Issue</option>
                                <option value="non_compliance">Non-Compliance</option>
                                <option value="breach">Contract Breach</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount ($) <span class="text-danger">*</span></label>
                            <input type="number" name="penalty_amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Issue Penalty</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Penalty List</h4>
                    <table class="table school-table-style" id="penaltyTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penalties as $penalty)
                            <tr>
                                <td>{{ date('d M Y', strtotime($penalty->penalty_date)) }}</td>
                                <td>{{ $penalty->vendor->vendor_name ?? 'N/A' }}</td>
                                <td><span class="badge badge-warning">{{ str_replace('_', ' ', $penalty->penalty_type) }}</span></td>
                                <td><strong class="text-danger">${{ number_format($penalty->penalty_amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge badge-primary">{{ ucfirst($penalty->status) }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" title="View Details"><i class="fas fa-eye"></i></button>
                                </td>
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
    $('#penaltyTable').DataTable({ responsive: true });
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
