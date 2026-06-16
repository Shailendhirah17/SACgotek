@extends('backEnd.master')
@section('title') Student Movements @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>RFID Movement Logs (Simulator)</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Hostel</a>
                <a href="#">Movements</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Simulated Scanner -->
            <div class="col-lg-3">
                <div class="white-box bg-dark text-white text-center p-4" style="border-radius: 15px; border: 4px solid #444;">
                    <h4 class="text-white mb-20"><i class="fas fa-wifi text-success blinking"></i> Scanner Sim</h4>
                    <form action="{{ route('hostel.movements.store') }}" method="POST">
                        @csrf
                        <div class="form-group text-left">
                            <label class="text-white">Scan Student Card <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="student_id" required style="width: 100%;">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->admission_no }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-left mt-3">
                            <label class="text-white">Select Gate / Hostel</label>
                            <select name="hostel_id" class="form-control" required>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }} Main Gate</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" name="direction" value="entry" class="btn btn-success btn-lg w-45 shadow"><i class="fas fa-sign-in-alt"></i> Entry</button>
                            <button type="submit" name="direction" value="exit" class="btn btn-danger btn-lg w-45 shadow"><i class="fas fa-sign-out-alt"></i> Exit</button>
                        </div>
                    </form>
                </div>
                <small class="text-muted mt-2 d-block text-center"><i class="fas fa-info-circle"></i> This form simulates what happens when an RFID card is tapped at the turnstile.</small>
            </div>

            <!-- Movement Log Table -->
            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Recent Movement Logs</h4>
                    <table class="table school-table-style" id="movementTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Student</th>
                                <th>Direction</th>
                                <th>Gate / Location</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                            <tr>
                                <td>{{ date('d M Y, h:i:s A', strtotime($movement->scanned_at)) }}</td>
                                <td>{{ $movement->student->full_name ?? 'Unknown' }}</td>
                                <td>
                                    @if($movement->direction == 'entry')
                                        <span class="badge badge-success"><i class="fas fa-arrow-right"></i> Entry</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-arrow-left"></i> Exit</span>
                                    @endif
                                </td>
                                <td>{{ $movement->hostel->hostel_name ?? 'Unknown' }} ({{ $movement->gate }})</td>
                                <td><span class="badge badge-secondary">{{ strtoupper($movement->scan_method) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.w-45 { width: 48%; }
.blinking { animation: blinker 1.5s linear infinite; }
@keyframes blinker { 50% { opacity: 0; } }
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#movementTable').DataTable({ responsive: true, order: [[0, 'desc']] });
    if($('.select2').length) { $('.select2').select2({ theme: 'bootstrap4' }); }
});
</script>
@endpush
