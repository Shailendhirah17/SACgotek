@extends('backEnd.master')
@section('title') Vendor Evaluations @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Performance Evaluations</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Vendor</a>
                <a href="#">Evaluations</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Add Evaluation</h4>
                    <form action="{{ route('vendor.evaluation.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Vendor <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="vendor_id" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Period <span class="text-danger">*</span></label>
                            <input type="text" name="evaluation_period" class="form-control" placeholder="e.g. Q1-2026" required>
                        </div>
                        <div class="form-group">
                            <label>Overall Score (1-5) <span class="text-danger">*</span></label>
                            <input type="number" name="overall_score" class="form-control" min="1" max="5" step="0.1" required>
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Save Evaluation</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Evaluation List</h4>
                    <table class="table school-table-style" id="evalTable">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Period</th>
                                <th>Score (out of 5)</th>
                                <th>Evaluated By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluations as $eval)
                            <tr>
                                <td>{{ $eval->vendor->vendor_name ?? 'N/A' }}</td>
                                <td>{{ $eval->evaluation_period }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress w-100 mr-2" style="height: 10px;">
                                            @php $percent = ($eval->overall_score / 5) * 100; @endphp
                                            <div class="progress-bar {{ $percent >= 80 ? 'bg-success' : ($percent >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                 style="width: {{ $percent }}%"></div>
                                        </div>
                                        <strong>{{ $eval->overall_score }}</strong>
                                    </div>
                                </td>
                                <td>{{ $eval->evaluator->full_name ?? 'Admin' }}</td>
                                <td>{{ $eval->created_at->format('d M Y') }}</td>
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
    $('#evalTable').DataTable({ responsive: true });
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
