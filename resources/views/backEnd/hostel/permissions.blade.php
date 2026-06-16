@extends('backEnd.master')
@section('title') Leave & Outing Permissions @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Leave & Outing Permissions</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Hostel</a>
                <a href="#">Permissions</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <h4 class="mb-30">Permission Requests</h4>
                    <table class="table school-table-style" id="permissionTable">
                        <thead>
                            <tr>
                                <th>Date Requested</th>
                                <th>Student</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $perm)
                            <tr>
                                <td>{{ $perm->created_at->format('d M Y') }}</td>
                                <td>{{ $perm->student->full_name ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $perm->permission_type)) }}</span></td>
                                <td>
                                    {{ date('d M h:i A', strtotime($perm->from_datetime)) }} <br>
                                    <small class="text-muted">to</small> <br>
                                    {{ date('d M h:i A', strtotime($perm->to_datetime)) }}
                                </td>
                                <td>{{ Str::limit($perm->reason, 40) }}</td>
                                <td>
                                    @if($perm->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($perm->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($perm->status == 'returned')
                                        <span class="badge badge-primary">Returned</span>
                                    @else
                                        <span class="badge badge-danger">{{ ucfirst($perm->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($perm->status == 'pending')
                                        <a href="{{ route('hostel.permission.status', [$perm->id, 'approved']) }}" class="btn btn-sm btn-success" title="Approve"><i class="fas fa-check"></i></a>
                                        <a href="{{ route('hostel.permission.status', [$perm->id, 'rejected']) }}" class="btn btn-sm btn-danger" title="Reject"><i class="fas fa-times"></i></a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Done</button>
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
    $('#permissionTable').DataTable({ responsive: true });
});
</script>
@endpush
