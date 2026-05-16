@extends('backEnd.master')
@section('title') Vaccination Records @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Vaccination Records</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Student Modules</a>
                <a href="#">Vaccination Records</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Vaccination Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-syringe mr-2 text-success"></i> Add Vaccination Record</h4>
                    <form action="{{ route('medical.vaccination.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Class</label>
                            <select name="class_id" id="vac_class_id" class="form-control" onchange="loadVacStudents(this.value)">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="vac_student_id" class="form-control">
                                <option value="">-- Select Student --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Vaccine Name <span class="text-danger">*</span></label>
                            <input type="text" name="vaccine_name" class="form-control" placeholder="e.g. BCG, Polio, Hepatitis B">
                        </div>
                        <div class="form-group">
                            <label>Date Given</label>
                            <input type="date" name="date_given" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label>Dose</label>
                            <input type="text" name="dose" class="form-control" placeholder="e.g. 1st, 2nd, Booster">
                        </div>
                        <div class="form-group">
                            <label>Administered By</label>
                            <input type="text" name="administered_by" class="form-control" placeholder="Doctor / Nurse name">
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Any observations..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-save mr-1"></i> Save Record
                        </button>
                    </form>
                </div>
            </div>

            {{-- Vaccination Records Table --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-success"></i> Vaccination Records</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="vacTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Vaccine</th>
                                    <th>Dose</th>
                                    <th>Date Given</th>
                                    <th>Administered By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $i => $vac)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @if($vac->student)
                                            {{ $vac->student->first_name }} {{ $vac->student->last_name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-success">{{ $vac->vaccine_name }}</span></td>
                                    <td>{{ $vac->dose ?? '—' }}</td>
                                    <td>{{ $vac->date_given ? \Carbon\Carbon::parse($vac->date_given)->format('d M Y') : '—' }}</td>
                                    <td>{{ $vac->administered_by ?? '—' }}</td>
                                    <td>
                                        <button type="button" class="primary-btn small bg-primary border-0 text-white" 
                                                data-toggle="modal" data-target="#editVaccinationModal{{ $vac->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('medical.vaccination.delete', $vac->id) }}"
                                           class="primary-btn small bg-danger border-0 text-white"
                                           onclick="return confirm('Delete this vaccination record?')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editVaccinationModal{{ $vac->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Vaccination</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('medical.vaccination.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="record_id" value="{{ $vac->id }}">
                                                        <div class="modal-body">
                                                            <div class="form-group text-left">
                                                                <label>Vaccine Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="vaccine_name" class="form-control" value="{{ $vac->vaccine_name }}" required>
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Date Given</label>
                                                                <input type="date" name="date_given" class="form-control" value="{{ $vac->date_given }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Dose</label>
                                                                <input type="text" name="dose" class="form-control" value="{{ $vac->dose }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Administered By</label>
                                                                <input type="text" name="administered_by" class="form-control" value="{{ $vac->administered_by }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Remarks</label>
                                                                <textarea name="remarks" class="form-control" rows="2">{{ $vac->remarks }}</textarea>
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
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No vaccination records found.
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
function loadVacStudents(classId) {
    if (!classId) return;
    $.get("{{ route('tc.get-students') }}", { class_id: classId }, function(data) {
        var opts = '<option value="">-- Select Student --</option>';
        $.each(data, function(i, s) {
            opts += '<option value="' + s.id + '">' + s.first_name + ' ' + s.last_name + '</option>';
        });
        $('#vac_student_id').html(opts);
    });
}
$(document).ready(function() {
    $('#vacTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
