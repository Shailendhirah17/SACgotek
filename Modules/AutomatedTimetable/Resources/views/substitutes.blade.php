@extends('backEnd.master')
@section('title')
Teacher Substitutions
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Automated Timetable - Subs Management</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">Dashboard</a>
                <a href="{{ route('automatedtimetable.index') }}">Timetable</a>
                <a href="#">Subs</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <h3 class="mb-20">Orphaned Periods (Requires Substitute)</h3>
                    <p>When a teacher marks themselves on Leave, their scheduled periods become orphaned. Assign substitute teachers from the available pool here.</p>
                    
                    <table class="display school-table school-table-style" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Class & Section</th>
                                <th>Subject</th>
                                <th>Original Teacher</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Placeholder for demonstration of workflow -->
                            <tr>
                                <td>Class 1 - A</td>
                                <td>Mathematics</td>
                                <td>John Doe (On Leave)</td>
                                <td>Monday, 10:00 AM</td>
                                <td>
                                    <form method="POST" action="{{ route('automatedtimetable.assign_substitute') }}">
                                        @csrf
                                        <select class="form-control mb-10" required>
                                            <option value="">Select Available Substitute</option>
                                            <option value="2">Jane Smith (Free Slot)</option>
                                            <option value="3">Mark Davis (Free Slot)</option>
                                        </select>
                                        <button class="primary-btn small tr-bg">Assign Sub</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
