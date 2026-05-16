@extends('backEnd.master')
@section('title') Room Allocation @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Room Allocation</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Hostel Management</a>
                <a href="#">Room Allocation</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Allocate Room Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-bed mr-2 text-info"></i> Allocate Room</h4>
                    <form action="{{ route('hostel.allocation.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Hostel <span class="text-danger">*</span></label>
                            <select name="hostel_id" id="alloc_hostel_id" class="form-control" onchange="loadRooms(this.value)" required>
                                <option value="">-- Select Hostel --</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Room <span class="text-danger">*</span></label>
                            <select name="room_id" id="alloc_room_id" class="form-control" required>
                                <option value="">-- Select Room --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <select id="alloc_class_id" class="form-control" onchange="loadAllocStudents(this.value)">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="alloc_student_id" class="form-control" required>
                                <option value="">-- Select Student --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Join Date <span class="text-danger">*</span></label>
                            <input type="date" name="join_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-check mr-1"></i> Allocate Room
                        </button>
                    </form>
                </div>
            </div>

            {{-- Allocations List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-info"></i> Current Allocations</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="allocTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Hostel</th>
                                    <th>Room</th>
                                    <th>Join Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $i => $alloc)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @if($alloc->student)
                                            {{ $alloc->student->first_name }} {{ $alloc->student->last_name }}
                                        @else —
                                        @endif
                                    </td>
                                    <td>{{ $alloc->hostel->hostel_name ?? '—' }}</td>
                                    <td>{{ $alloc->room->room_no ?? '—' }}</td>
                                    <td>{{ $alloc->join_date ? \Carbon\Carbon::parse($alloc->join_date)->format('d M Y') : '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $alloc->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($alloc->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($alloc->status === 'active')
                                            <a href="{{ route('hostel.vacate', $alloc->id) }}"
                                               class="primary-btn small bg-warning border-0"
                                               onclick="return confirm('Vacate this student?')">
                                                <i class="fas fa-sign-out-alt"></i> Vacate
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No room allocations yet.
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
function loadRooms(hostelId) {
    if (!hostelId) return;
    $.get("{{ route('hostel.get-rooms') }}", { hostel_id: hostelId }, function(data) {
        var opts = '<option value="">-- Select Room --</option>';
        $.each(data, function(i, r) {
            opts += '<option value="' + r.id + '">Room ' + r.room_no + ' (' + r.room_type + ') — ' + r.status + '</option>';
        });
        $('#alloc_room_id').html(opts);
    });
}
function loadAllocStudents(classId) {
    if (!classId) return;
    $.get("{{ route('tc.get-students') }}", { class_id: classId }, function(data) {
        var opts = '<option value="">-- Select Student --</option>';
        $.each(data, function(i, s) {
            opts += '<option value="' + s.id + '">' + s.first_name + ' ' + s.last_name + '</option>';
        });
        $('#alloc_student_id').html(opts);
    });
}
$(document).ready(function() {
    $('#allocTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
