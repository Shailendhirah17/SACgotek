@extends('backEnd.master')
@section('title') Transfer Certificate (TC) List @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Transfer Certificate (TC)</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Student Modules</a>
                <a href="#">TC List</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">

        {{-- Issue TC Form --}}
        <div class="row">
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-file-alt mr-2 text-primary"></i> Issue TC</h4>
                    <form action="{{ route('tc.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Class <span class="text-danger">*</span></label>
                            <select name="class_id" id="class_id" class="form-control" onchange="loadStudents(this.value)">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-control">
                                <option value="">-- Select Student --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>TC Number</label>
                            <input type="text" name="tc_no" class="form-control" placeholder="e.g. TC/2026/001">
                        </div>
                        <div class="form-group">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label>Reason for Leaving</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-paper-plane mr-1"></i> Issue TC
                        </button>
                    </form>
                </div>
            </div>

            {{-- TC List Table --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-primary"></i> TC Records</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="tcTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Admission No.</th>
                                    <th>TC Number</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tcs as $i => $tc)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @if($tc->student)
                                            {{ $tc->student->first_name }} {{ $tc->student->last_name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $tc->student->admission_no ?? '—' }}</td>
                                    <td><span class="badge badge-info">{{ $tc->tc_no ?? '—' }}</span></td>
                                    <td>{{ $tc->date ? \Carbon\Carbon::parse($tc->date)->format('d M Y') : '—' }}</td>
                                    <td>{{ Str::limit($tc->reason, 40) }}</td>
                                    <td>
                                        <a href="{{ route('tc.show', $tc->id) }}" class="primary-btn small fix-gr-bg" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="primary-btn small fix-gr-bg" 
                                                onclick="editTC({{ $tc->id }}, '{{ $tc->tc_no }}', '{{ $tc->date }}', `{{ $tc->reason }}`)" 
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('tc.delete', $tc->id) }}"
                                           class="primary-btn small bg-danger border-0"
                                           onclick="return confirm('Delete this TC?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No TC records found.
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

<!-- Edit TC Modal -->
<div class="modal fade" id="editTCModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit TC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tc.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="tc_id" id="edit_tc_id">
                    <div class="form-group">
                        <label>TC Number</label>
                        <input type="text" name="tc_no" id="edit_tc_no" class="form-control" placeholder="e.g. TC/2026/001">
                    </div>
                    <div class="form-group">
                        <label>Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="edit_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Reason for Leaving</label>
                        <textarea name="reason" id="edit_reason" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn small fix-gr-bg bg-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="primary-btn small fix-gr-bg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loadStudents(classId) {
    if (!classId) return;
    $.get("{{ route('tc.get-students') }}", { class_id: classId }, function(data) {
        var opts = '<option value="">-- Select Student --</option>';
        $.each(data, function(i, s) {
            opts += '<option value="' + s.id + '">' + s.first_name + ' ' + s.last_name + '</option>';
        });
        $('#student_id').html(opts);
    });
}
function editTC(id, tc_no, date, reason) {
    $('#edit_tc_id').val(id);
    $('#edit_tc_no').val(tc_no);
    $('#edit_date').val(date);
    $('#edit_reason').val(reason);
    $('#editTCModal').modal('show');
}
$(document).ready(function() {
    $('#tcTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
