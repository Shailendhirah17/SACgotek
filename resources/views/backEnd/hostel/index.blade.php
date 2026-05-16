@extends('backEnd.master')
@section('title') Hostel Management @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Hostel Management</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Hostel Management</a>
                <a href="#">Hostel List</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Hostel Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-hotel mr-2 text-primary"></i> Add Hostel</h4>
                    <form action="{{ route('hostel.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Hostel Name <span class="text-danger">*</span></label>
                            <input type="text" name="hostel_name" class="form-control" placeholder="e.g. Boys Hostel Block A" required>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="boys">Boys</option>
                                <option value="girls">Girls</option>
                                <option value="mixed">Mixed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Capacity (rooms)</label>
                            <input type="number" name="capacity" class="form-control" placeholder="Total rooms" min="0">
                        </div>
                        <div class="form-group">
                            <label>Warden Name</label>
                            <input type="text" name="warden_name" class="form-control" placeholder="Warden's name">
                        </div>
                        <div class="form-group">
                            <label>Warden Phone</label>
                            <input type="text" name="warden_phone" class="form-control" placeholder="+91 XXXXXXXXXX">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Hostel address..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-plus mr-1"></i> Add Hostel
                        </button>
                    </form>
                </div>

                {{-- Quick Links --}}
                <div class="white-box mt-20">
                    <h5 class="mb-15">Quick Links</h5>
                    <a href="{{ route('hostel.rooms') }}" class="primary-btn small fix-gr-bg d-block mb-10 text-center">
                        <i class="fas fa-door-open mr-1"></i> Manage Rooms
                    </a>
                    <a href="{{ route('hostel.meals') }}" class="primary-btn small fix-gr-bg d-block mb-10 text-center">
                        <i class="fas fa-utensils mr-1"></i> Manage Meals
                    </a>
                    <a href="{{ route('hostel.index') }}" class="primary-btn small fix-gr-bg d-block text-center">
                        <i class="fas fa-list mr-1"></i> Hostel List
                    </a>
                </div>
            </div>

            {{-- Hostel List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-primary"></i> Hostel List</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="hostelTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Hostel Name</th>
                                    <th>Type</th>
                                    <th>Capacity</th>
                                    <th>Warden</th>
                                    <th>Warden Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hostels as $i => $hostel)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $hostel->hostel_name }}</strong></td>
                                    <td>
                                        @php
                                            $typeBadge = match($hostel->type) {
                                                'boys' => 'primary',
                                                'girls' => 'danger',
                                                default => 'success',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $typeBadge }}">{{ ucfirst($hostel->type) }}</span>
                                    </td>
                                    <td>{{ $hostel->capacity ?? 0 }}</td>
                                    <td>{{ $hostel->warden_name ?? '—' }}</td>
                                    <td>{{ $hostel->warden_phone ?? '—' }}</td>
                                    <td>
                                        <button type="button" class="primary-btn small bg-primary border-0 text-white" 
                                                data-toggle="modal" data-target="#editHostelModal{{ $hostel->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('hostel.delete', $hostel->id) }}"
                                           class="primary-btn small bg-danger border-0 text-white"
                                           onclick="return confirm('Delete this hostel?')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editHostelModal{{ $hostel->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Hostel</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('hostel.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="hostel_id" value="{{ $hostel->id }}">
                                                        <div class="modal-body">
                                                            <div class="form-group text-left">
                                                                <label>Hostel Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="hostel_name" class="form-control" value="{{ $hostel->hostel_name }}" required>
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Type</label>
                                                                <select name="type" class="form-control">
                                                                    <option value="boys" {{ $hostel->type == 'boys' ? 'selected' : '' }}>Boys</option>
                                                                    <option value="girls" {{ $hostel->type == 'girls' ? 'selected' : '' }}>Girls</option>
                                                                    <option value="mixed" {{ $hostel->type == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Capacity (rooms)</label>
                                                                <input type="number" name="capacity" class="form-control" value="{{ $hostel->capacity }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Warden Name</label>
                                                                <input type="text" name="warden_name" class="form-control" value="{{ $hostel->warden_name }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Warden Phone</label>
                                                                <input type="text" name="warden_phone" class="form-control" value="{{ $hostel->warden_phone }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Address</label>
                                                                <textarea name="address" class="form-control" rows="2">{{ $hostel->address }}</textarea>
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No hostels added yet.
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
    $('#hostelTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
