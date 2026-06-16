@extends('backEnd.master')
@section('title') Vendor Documents @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Compliance Documents</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Vendor</a>
                <a href="#">Documents</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Upload Document</h4>
                    <form action="{{ route('vendor.document.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label>Document Type</label>
                            <select name="document_type" class="form-control">
                                <option value="registration">Business Registration</option>
                                <option value="tax">Tax/GST Certificate</option>
                                <option value="license">Trade License</option>
                                <option value="insurance">Insurance Policy</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Document Name <span class="text-danger">*</span></label>
                            <input type="text" name="document_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>File Upload</label>
                            <input type="file" name="file" class="form-control-file">
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Upload</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Document Repository</h4>
                    <table class="table school-table-style" id="docTable">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Type</th>
                                <th>Document Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $doc)
                            <tr>
                                <td>{{ $doc->vendor->vendor_name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($doc->document_type) }}</td>
                                <td>{{ $doc->document_name }}</td>
                                <td>
                                    @if($doc->verification_status == 'verified')
                                        <span class="badge badge-success">Verified</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Download"><i class="fas fa-download"></i></button>
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
    $('#docTable').DataTable({ responsive: true });
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
