@extends('backEnd.master')
@section('title') Vendor Agreements @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Contracts & Agreements</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Vendor</a>
                <a href="#">Agreements</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Add Agreement</h4>
                    <form action="{{ route('vendor.agreement.store') }}" method="POST">
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
                            <label>Agreement Title <span class="text-danger">*</span></label>
                            <input type="text" name="agreement_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Save Agreement</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Agreement List</h4>
                    <table class="table school-table-style" id="agreementTable">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Title</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agreements as $agr)
                            <tr>
                                <td>{{ $agr->vendor->vendor_name ?? 'N/A' }}</td>
                                <td>{{ $agr->agreement_title }}</td>
                                <td>
                                    {{ $agr->start_date ? date('d M Y', strtotime($agr->start_date)) : 'N/A' }} - 
                                    {{ $agr->end_date ? date('d M Y', strtotime($agr->end_date)) : 'N/A' }}
                                </td>
                                <td>
                                    @if($agr->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @elseif($agr->status == 'expired')
                                        <span class="badge badge-danger">Expired</span>
                                    @else
                                        <span class="badge badge-warning">{{ ucfirst($agr->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
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
    $('#agreementTable').DataTable({ responsive: true });
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
