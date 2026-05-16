@extends('backEnd.master')
@section('title')
Student Self-Service
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Student Self-Service</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">Dashboard</a>
                <a href="#">Advanced Portal</a>
                <a href="#">Self-Service</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="white-box student-details text-center mb-30">
                    <img src="{{ isset($student) && file_exists($student->student_photo) ? asset($student->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <h3>{{$student->full_name ?? 'Student Name'}}</h3>
                    <p class="mb-0">Roll No: {{$student->roll_no ?? 'N/A'}} | Admn No: {{$student->admission_no ?? 'N/A'}}</p>
                    <p>Class: {{$student->class->class_name ?? ''}} ({{$student->section->section_name ?? ''}})</p>
                    <hr>
                    <a href="#" class="primary-btn small fix-gr-bg w-100 mb-10"><i class="ti-download"></i> Download Conduct Cert</a>
                    <a href="#" class="primary-btn small tr-bg w-100"><i class="ti-download"></i> Download Transfer Cert</a>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="white-box mb-30">
                    <h3 class="mb-20">My Asset Management</h3>
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#homework">Assignments ({{$homeworks->count() ?? 0}})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#materials">Study Materials ({{$study_materials->count() ?? 0}})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#clubs">Co-curricular & Clubs</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-30">
                        <div id="homework" class="container tab-pane active"><br>
                            @if(isset($homeworks) && $homeworks->count() > 0)
                                <table class="table school-table-style">
                                    <thead><tr><th>Date</th><th>Subject</th><th>Marks</th><th>Action</th></tr></thead>
                                    <tbody>
                                        @foreach($homeworks as $hw)
                                        <tr>
                                            <td>{{dateConvert($hw->homework_date)}}</td>
                                            <td>{{$hw->subjects->subject_name ?? ''}}</td>
                                            <td>{{$hw->marks}}</td>
                                            <td><a href="#" class="primary-btn small tr-bg">View</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No pending assignments.</p>
                            @endif
                        </div>
                        <div id="materials" class="container tab-pane fade"><br>
                            @if(isset($study_materials) && $study_materials->count() > 0)
                                <table class="table school-table-style">
                                    <thead><tr><th>Title</th><th>Date</th><th>Action</th></tr></thead>
                                    <tbody>
                                        @foreach($study_materials as $material)
                                        <tr>
                                            <td>{{$material->content_title}}</td>
                                            <td>{{dateConvert($material->upload_date)}}</td>
                                            <td>
                                                @if($material->upload_file)
                                                <a href="{{asset($material->upload_file)}}" target="_blank" class="primary-btn small fix-gr-bg"><i class="ti-download"></i> Download</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No study materials uploaded currently.</p>
                            @endif
                        </div>
                        <div id="clubs" class="container tab-pane fade"><br>
                            <div class="alert alert-info">Event Registration and Club selection portal opens next semester.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
