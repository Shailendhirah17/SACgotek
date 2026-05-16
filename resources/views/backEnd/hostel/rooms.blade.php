@extends('backEnd.master')
@section('title') Hostel Rooms @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Hostel Rooms</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Hostel Management</a>
                <a href="#">Hostel Rooms</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Room Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-door-open mr-2 text-success"></i> Add Room</h4>
                    <form action="{{ route('hostel.room.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Hostel <span class="text-danger">*</span></label>
                            <select name="hostel_id" class="form-control" required>
                                <option value="">-- Select Hostel --</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Room No. <span class="text-danger">*</span></label>
                            <input type="text" name="room_no" class="form-control" placeholder="e.g. 101, A-12" required>
                        </div>
                        <div class="form-group">
                            <label>Room Type</label>
                            <select name="room_type" class="form-control">
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Triple">Triple</option>
                                <option value="Dormitory">Dormitory</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Capacity (beds)</label>
                            <input type="number" name="capacity" class="form-control" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label>Fee / Month (₹)</label>
                            <input type="number" step="0.01" name="fee_per_month" class="form-control" placeholder="0.00">
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-plus mr-1"></i> Add Room
                        </button>
                    </form>
                </div>
            </div>

            {{-- Room List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-success"></i> Room List</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="roomTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Hostel</th>
                                    <th>Room No.</th>
                                    <th>Type</th>
                                    <th>Capacity</th>
                                    <th>Fee/Month</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $i => $room)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $room->hostel->hostel_name ?? '—' }}</td>
                                    <td><strong>{{ $room->room_no }}</strong></td>
                                    <td>{{ $room->room_type ?? '—' }}</td>
                                    <td>{{ $room->capacity }}</td>
                                    <td>₹{{ number_format($room->fee_per_month, 2) }}</td>
                                    <td>
                                        @php
                                            $statusBadge = match($room->status) {
                                                'available' => 'success',
                                                'occupied' => 'warning',
                                                'maintenance' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $statusBadge }}">{{ ucfirst($room->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('hostel.room.delete', $room->id) }}"
                                           class="primary-btn small bg-danger border-0"
                                           onclick="return confirm('Delete this room?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No rooms added yet.
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
    $('#roomTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
