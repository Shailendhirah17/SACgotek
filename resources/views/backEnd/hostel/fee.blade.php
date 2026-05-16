@extends('backEnd.master')
@section('title') Hostel Fees @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Hostel Fees</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Hostel Management</a>
                <a href="#">Hostel Fees</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Fee Record Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-coins mr-2 text-warning"></i> Add Fee Record</h4>
                    <form action="{{ route('hostel.fee.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Hostel <span class="text-danger">*</span></label>
                            <select name="hostel_id" id="fee_hostel_id" class="form-control" onchange="loadFeeRooms(this.value)" required>
                                <option value="">-- Select Hostel --</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <select name="room_id" id="fee_room_id" class="form-control">
                                <option value="">-- Select Room --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <select id="fee_class_id" class="form-control" onchange="loadFeeStudents(this.value)">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="fee_student_id" class="form-control" required>
                                <option value="">-- Select Student --</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Month <span class="text-danger">*</span></label>
                                    <select name="month" class="form-control" required>
                                        @foreach(range(1,12) as $m)
                                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                                {{ date('F', mktime(0,0,0,$m,1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Year <span class="text-danger">*</span></label>
                                    <select name="year" class="form-control" required>
                                        @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="Monthly fee amount" required>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-plus mr-1"></i> Add Fee Record
                        </button>
                    </form>
                </div>
            </div>

            {{-- Fees List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-warning"></i> Hostel Fee Records</h4>

                    {{-- Summary row --}}
                    <div class="row mb-20">
                        <div class="col-4">
                            <div class="card text-center" style="border-left:4px solid #4CAF50;">
                                <div class="card-body py-2">
                                    <small class="text-muted">Total Collected</small>
                                    <h5 class="mb-0 text-success">
                                        ₹{{ number_format(($fees->where('status','paid') ?? collect())->sum('amount'), 2) }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card text-center" style="border-left:4px solid #F44336;">
                                <div class="card-body py-2">
                                    <small class="text-muted">Pending</small>
                                    <h5 class="mb-0 text-danger">
                                        ₹{{ number_format(($fees->where('status','unpaid') ?? collect())->sum('amount'), 2) }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card text-center" style="border-left:4px solid #FF9800;">
                                <div class="card-body py-2">
                                    <small class="text-muted">Total Records</small>
                                    <h5 class="mb-0 text-warning">{{ ($fees ?? collect())->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="feeTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Hostel</th>
                                    <th>Month/Year</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees ?? [] as $i => $fee)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @if($fee->student)
                                            {{ $fee->student->firstname }} {{ $fee->student->lastname }}
                                        @else —
                                        @endif
                                    </td>
                                    <td>{{ $fee->hostel->hostel_name ?? '—' }}</td>
                                    <td>{{ date('F', mktime(0,0,0,$fee->month,1)) }} {{ $fee->year }}</td>
                                    <td><strong>₹{{ number_format($fee->amount, 2) }}</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $fee->status === 'paid' ? 'success' : 'danger' }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($fee->status === 'unpaid')
                                            <a href="{{ route('hostel.fee.pay', $fee->id) }}"
                                               class="primary-btn small fix-gr-bg"
                                               onclick="return confirm('Mark as paid?')">
                                                <i class="fas fa-check-circle"></i> Pay
                                            </a>
                                        @else
                                            <span class="text-success"><i class="fas fa-check-circle"></i> Paid</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No hostel fee records yet.
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
function loadFeeRooms(hostelId) {
    if (!hostelId) return;
    $.get("{{ route('hostel.get-rooms') }}", { hostel_id: hostelId }, function(data) {
        var opts = '<option value="">-- Select Room --</option>';
        $.each(data, function(i, r) {
            opts += '<option value="' + r.id + '">Room ' + r.room_no + '</option>';
        });
        $('#fee_room_id').html(opts);
    });
}
function loadFeeStudents(classId) {
    if (!classId) return;
    $.get("{{ route('tc.get-students') }}", { class_id: classId }, function(data) {
        var opts = '<option value="">-- Select Student --</option>';
        $.each(data, function(i, s) {
            opts += '<option value="' + s.id + '">' + s.firstname + ' ' + s.lastname + '</option>';
        });
        $('#fee_student_id').html(opts);
    });
}
$(document).ready(function() {
    $('#feeTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
