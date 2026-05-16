@extends('backEnd.master')
@section('title') Vendor Management @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Vendor Management</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Vendor &amp; Accounts</a>
                <a href="#">Vendor List</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Vendor Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-building mr-2 text-info"></i> Add Vendor</h4>
                    <form action="{{ route('vendor.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Vendor Name <span class="text-danger">*</span></label>
                            <input type="text" name="vendor_name" class="form-control" placeholder="Company / individual name" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="Contact person name">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 XXXXXXXXXX">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="vendor@example.com">
                        </div>
                        <div class="form-group">
                            <label>GSTIN</label>
                            <input type="text" name="gstin" class="form-control" placeholder="GST number">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Full address..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-plus mr-1"></i> Add Vendor
                        </button>
                    </form>
                </div>
            </div>

            {{-- Vendor List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-info"></i> Vendor List</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="vendorTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>GSTIN</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendors as $i => $vendor)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $vendor->vendor_name }}</strong></td>
                                    <td>{{ $vendor->contact_person ?? '—' }}</td>
                                    <td>{{ $vendor->phone ?? '—' }}</td>
                                    <td>{{ $vendor->email ?? '—' }}</td>
                                    <td>{{ $vendor->gstin ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $vendor->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($vendor->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="primary-btn small bg-primary border-0 text-white" 
                                                data-toggle="modal" data-target="#editVendorModal{{ $vendor->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('vendor.delete', $vendor->id) }}"
                                           class="primary-btn small bg-danger border-0 text-white"
                                           onclick="return confirm('Delete this vendor?')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editVendorModal{{ $vendor->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Vendor</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('vendor.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                                        <div class="modal-body">
                                                            <div class="form-group text-left">
                                                                <label>Vendor Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="vendor_name" class="form-control" value="{{ $vendor->vendor_name }}" required>
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Contact Person</label>
                                                                <input type="text" name="contact_person" class="form-control" value="{{ $vendor->contact_person }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Phone</label>
                                                                <input type="text" name="phone" class="form-control" value="{{ $vendor->phone }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Email</label>
                                                                <input type="email" name="email" class="form-control" value="{{ $vendor->email }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>GSTIN</label>
                                                                <input type="text" name="gstin" class="form-control" value="{{ $vendor->gstin }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Address</label>
                                                                <textarea name="address" class="form-control" rows="3">{{ $vendor->address }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="primary-btn fix-gr-bg">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No vendors registered yet.
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
    $('#vendorTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
