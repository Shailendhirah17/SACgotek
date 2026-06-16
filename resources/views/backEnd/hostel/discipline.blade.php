@extends('backEnd.master')
@section('title') Discipline & Incident Management @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Discipline & Incidents</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Hostel</a>
                <a href="#">Discipline</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Report Incident</h4>
                    <form action="{{ route('hostel.discipline.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
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
                            <label>Incident Type <span class="text-danger">*</span></label>
                            <select name="incident_type" class="form-control" required>
                                <option value="noise">Noise Violation</option>
                                <option value="unauthorized_exit">Unauthorized Exit</option>
                                <option value="damage">Property Damage</option>
                                <option value="misconduct">Misconduct</option>
                                <option value="late_entry">Late Entry</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Severity</label>
                            <select name="severity" class="form-control">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Report Incident</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Disciplinary Records</h4>
                    <table class="table school-table-style" id="disciplineTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Incident</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disciplines as $record)
                            <tr>
                                <td>{{ date('d M Y', strtotime($record->incident_date)) }}</td>
                                <td>{{ $record->student->full_name ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $record->incident_type)) }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($record->description, 30) }}</small>
                                </td>
                                <td>
                                    @if($record->severity == 'critical' || $record->severity == 'high')
                                        <span class="badge badge-danger">{{ ucfirst($record->severity) }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ ucfirst($record->severity) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ ucfirst($record->status) }}</span>
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
    $('#disciplineTable').DataTable({ responsive: true });
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
