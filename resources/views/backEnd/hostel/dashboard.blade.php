@extends('backEnd.master')
@section('title') Hostel Dashboard @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Hostel Dashboard</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Hostel</a>
                <a href="#">Dashboard</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row mb-40">
            <!-- Summary Cards -->
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Total Hostels</h4>
                            <h2 class="text-primary">{{ $totalHostels }}</h2>
                        </div>
                        <i class="fas fa-building fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Total Rooms</h4>
                            <h2 class="text-success">{{ $totalRooms }}</h2>
                        </div>
                        <i class="fas fa-door-open fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Allocated Students</h4>
                            <h2 class="text-warning">{{ $totalStudents }}</h2>
                        </div>
                        <i class="fas fa-bed fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20">
                <div class="white-box dashboard-card bg-primary text-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="text-white">Quick Links</h4>
                            <a href="{{ route('hostel.index') }}" class="btn btn-light btn-sm mt-2 font-weight-bold">Hostel List</a>
                        </div>
                        <i class="fas fa-link fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="white-box">
                    <h4 class="mb-20">Recent Movements (RFID Simulator)</h4>
                    <table class="table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Direction</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $move)
                            <tr>
                                <td>{{ $move->student->full_name ?? 'N/A' }}</td>
                                <td>
                                    @if($move->direction == 'entry')
                                        <span class="badge badge-success"><i class="fas fa-arrow-right"></i> Entry</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-arrow-left"></i> Exit</span>
                                    @endif
                                </td>
                                <td>{{ date('h:i A - d M Y', strtotime($move->scanned_at)) }}</td>
                            </tr>
                            @endforeach
                            @if($recentMovements->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-muted">No recent movements</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="white-box">
                    <h4 class="mb-20">Pending Leave/Outing Requests</h4>
                    <table class="table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingPermissions as $perm)
                            <tr>
                                <td>{{ $perm->student->full_name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($perm->reason, 30) }}</td>
                                <td>
                                    <a href="{{ route('hostel.permission.status', [$perm->id, 'approved']) }}" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                    <a href="{{ route('hostel.permission.status', [$perm->id, 'rejected']) }}" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @if($pendingPermissions->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-muted">No pending requests</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.dashboard-card { padding: 25px; border-radius: 10px; transition: all 0.3s ease; }
.dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
</style>
@endsection
