@extends('backEnd.master')
@section('title') Issue Books @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Issue Books</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Library &amp; Book Bank</a>
                <a href="#">Issue Books</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Issue Book Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-book-reader mr-2 text-warning"></i> Issue a Book</h4>
                    <form action="{{ route('book-bank.issue.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Select Book <span class="text-danger">*</span></label>
                            <select name="book_id" class="form-control" required>
                                <option value="">-- Select Book --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ $book->available_copies < 1 ? 'disabled' : '' }}>
                                        {{ $book->book_name }}
                                        (Available: {{ $book->available_copies }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <select name="class_id" id="iss_class_id" class="form-control" onchange="loadIssStudents(this.value)">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="iss_student_id" class="form-control" required>
                                <option value="">-- Select Student --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Issue Date <span class="text-danger">*</span></label>
                            <input type="date" name="issued_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+14 days')) }}">
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-paper-plane mr-1"></i> Issue Book
                        </button>
                    </form>
                </div>
            </div>

            {{-- Issued Books List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-warning"></i> Issued Books</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="issueTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Book</th>
                                    <th>Student</th>
                                    <th>Issued Date</th>
                                    <th>Due Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues ?? [] as $i => $issue)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $issue->book->book_name ?? '—' }}</td>
                                    <td>
                                        @if($issue->student)
                                            {{ $issue->student->first_name }} {{ $issue->student->last_name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $issue->issued_date ? \Carbon\Carbon::parse($issue->issued_date)->format('d M Y') : '—' }}</td>
                                    <td>{{ $issue->due_date ? \Carbon\Carbon::parse($issue->due_date)->format('d M Y') : '—' }}</td>
                                    <td>{{ $issue->return_date ? \Carbon\Carbon::parse($issue->return_date)->format('d M Y') : '—' }}</td>
                                    <td>
                                        @php
                                            $badge = match($issue->status) {
                                                'issued' => 'warning',
                                                'returned' => 'success',
                                                'overdue' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badge }}">{{ ucfirst($issue->status) }}</span>
                                    </td>
                                    <td>
                                        @if($issue->status === 'issued')
                                            <a href="{{ route('book-bank.return', $issue->id) }}"
                                               class="primary-btn small fix-gr-bg"
                                               onclick="return confirm('Mark as returned?')">
                                                <i class="fas fa-undo"></i> Return
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No issued books found.
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
function loadIssStudents(classId) {
    if (!classId) return;
    $.get("{{ route('tc.get-students') }}", { class_id: classId }, function(data) {
        var opts = '<option value="">-- Select Student --</option>';
        $.each(data, function(i, s) {
            opts += '<option value="' + s.id + '">' + s.first_name + ' ' + s.last_name + '</option>';
        });
        $('#iss_student_id').html(opts);
    });
}
$(document).ready(function() {
    $('#issueTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
