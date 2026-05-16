@extends('backEnd.master')
@section('title') Medical Records @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Medical Records</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Student Modules</a>
                <a href="#">Medical Records</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Medical Record Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-notes-medical mr-2 text-danger"></i> Add Medical Record</h4>
                    <form action="{{ route('medical.records.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Class</label>
                            <select name="class_id" id="med_class_id" class="form-control" onchange="loadMedStudents(this.value)">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="med_student_id" class="form-control">
                                <option value="">-- Select Student --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Blood Group</label>
                            <select name="blood_group" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Weight (kg)</label>
                                    <input type="number" step="0.1" name="weight" class="form-control" placeholder="e.g. 45.5">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Height (cm)</label>
                                    <input type="number" step="0.1" name="height" class="form-control" placeholder="e.g. 150">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Allergies</label>
                            <input type="text" name="allergies" class="form-control" placeholder="e.g. Peanuts, Dust">
                        </div>
                        <div class="form-group">
                            <label>Medical History</label>
                            <textarea name="medical_history" class="form-control" rows="3" placeholder="Any known conditions..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Current Medications</label>
                            <textarea name="current_medications" class="form-control" rows="2" placeholder="If any..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-save mr-1"></i> Save Record
                        </button>
                    </form>
                </div>
            </div>

            {{-- Medical Records Table --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-danger"></i> Medical Records</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="medTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Blood Group</th>
                                    <th>Weight</th>
                                    <th>Height</th>
                                    <th>Allergies</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $i => $rec)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @if($rec->student)
                                            {{ $rec->student->first_name }} {{ $rec->student->last_name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rec->blood_group)
                                            <span class="badge badge-danger">{{ $rec->blood_group }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $rec->weight ? $rec->weight.' kg' : '—' }}</td>
                                    <td>{{ $rec->height ? $rec->height.' cm' : '—' }}</td>
                                    <td>{{ $rec->allergies ?? '—' }}</td>
                                    <td>
                                        <button type="button" class="primary-btn small fix-gr-bg" 
                                                onclick="editMedical({{ $rec->id }}, '{{ $rec->blood_group }}', '{{ $rec->weight }}', '{{ $rec->height }}', `{{ $rec->allergies }}`, `{{ $rec->medical_history }}`, `{{ $rec->current_medications }}`)" 
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('medical.records.delete', $rec->id) }}"
                                           class="primary-btn small bg-danger border-0"
                                           onclick="return confirm('Delete this record?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No medical records found.
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

<!-- Edit Medical Modal -->
<div class="modal fade" id="editMedicalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Medical Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('medical.records.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="record_id" id="edit_record_id">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select name="blood_group" id="edit_blood_group" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}">{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Weight (kg)</label>
                                <input type="number" step="0.1" name="weight" id="edit_weight" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Height (cm)</label>
                                <input type="number" step="0.1" name="height" id="edit_height" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Allergies</label>
                        <input type="text" name="allergies" id="edit_allergies" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Medical History</label>
                        <textarea name="medical_history" id="edit_medical_history" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Current Medications</label>
                        <textarea name="current_medications" id="edit_current_medications" class="form-control" rows="2"></textarea>
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
function loadMedStudents(classId) {
    if (!classId) return;
    $.get("{{ route('tc.get-students') }}", { class_id: classId }, function(data) {
        var opts = '<option value="">-- Select Student --</option>';
        $.each(data, function(i, s) {
            opts += '<option value="' + s.id + '">' + s.first_name + ' ' + s.last_name + '</option>';
        });
        $('#med_student_id').html(opts);
    });
}
function editMedical(id, blood_group, weight, height, allergies, history, medications) {
    $('#edit_record_id').val(id);
    $('#edit_blood_group').val(blood_group);
    $('#edit_weight').val(weight);
    $('#edit_height').val(height);
    $('#edit_allergies').val(allergies);
    $('#edit_medical_history').val(history);
    $('#edit_current_medications').val(medications);
    $('#editMedicalModal').modal('show');
}
$(document).ready(function() {
    $('#medTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
