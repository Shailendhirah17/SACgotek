@extends('backEnd.master')
@section('title') Hostel Visitors @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Visitor Management</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Hostel</a>
                <a href="#">Visitors</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Visitor Check-In</h4>
                    <form action="{{ route('hostel.visitor.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Student to Visit <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="student_id" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->admission_no }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Hostel <span class="text-danger">*</span></label>
                            <select name="hostel_id" class="form-control" required>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Visitor Name <span class="text-danger">*</span></label>
                            <input type="text" name="visitor_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Visitor Phone</label>
                            <input type="text" name="visitor_phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Relationship (e.g. Parent, Sibling)</label>
                            <input type="text" name="relationship" class="form-control">
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Check In Visitor</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Visitor Log</h4>
                    <table class="table school-table-style" id="visitorTable">
                        <thead>
                            <tr>
                                <th>Check-In Time</th>
                                <th>Visitor Name</th>
                                <th>Student</th>
                                <th>Relationship</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitors as $visitor)
                            <tr>
                                <td>{{ date('d M Y, h:i A', strtotime($visitor->check_in)) }}</td>
                                <td>
                                    <strong>{{ $visitor->visitor_name }}</strong><br>
                                    <small class="text-muted">{{ $visitor->visitor_phone }}</small>
                                </td>
                                <td>{{ $visitor->student->full_name ?? 'N/A' }}</td>
                                <td>{{ $visitor->relationship }}</td>
                                <td>
                                    @if($visitor->status == 'checked_in')
                                        <span class="badge badge-warning">Inside Campus</span>
                                    @else
                                        <span class="badge badge-success">Checked Out</span>
                                    @endif
                                </td>
                                <td>
                                    @if($visitor->status == 'checked_in')
                                        <button class="btn btn-sm btn-danger">Check Out</button>
                                    @endif
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
    $('#visitorTable').DataTable({ responsive: true });
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
