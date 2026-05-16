@extends('backEnd.master')
@section('title')
@lang('advancedstudentportal::app.360_student_view')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>360 Student View</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Advanced Portal</a>
                <a href="#">360 Student View</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <form method="GET" action="{{ route('asp.student_view') }}">
                        <div class="row">
                            <div class="col-lg-4 mt-30-md">
                                <select class="niceSelect w-100 bb form-control" name="class_id" required>
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                    @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <div class="input-effect">
                                    <input class="primary-input form-control" type="text" name="name" value="{{ request('name') }}">
                                    <label>@lang('student.search_by_name')</label>
                                    <span class="focus-border"></span>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(isset($students) && count($students) > 0)
        <div class="row mt-40">
            @foreach($students as $student)
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="student-meta-box">
                    <div class="white-box student-details rtl-border">
                        <div class="student-meta-top text-center">
                            <img src="{{ file_exists($student->student_photo) ? asset($student->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            <h3 class="mt-20"><strong>{{$student->full_name}}</strong></h3>
                            <p class="mb-0">Class: {{$student->class->class_name ?? ''}} ({{$student->section->section_name ?? ''}})</p>
                            <p>Roll No: {{$student->roll_no}} | Admn No: {{$student->admission_no}}</p>
                        </div>
                        <hr>
                        <div class="student-meta-bottom">
                            <h5 class="mb-10"><strong>Demographics & Medical</strong></h5>
                            <ul class="list-unstyled">
                                <li><strong>Gender:</strong> {{$student->gender->base_setup_name ?? 'N/A'}}</li>
                                <li><strong>Blood Group:</strong> {{$student->bloodGroup->base_setup_name ?? 'N/A'}}</li>
                                <li><strong>Religion:</strong> {{$student->religion->base_setup_name ?? 'N/A'}}</li>
                                <li><strong>DOB (Age):</strong> {{dateConvert($student->date_of_birth)}} ({{$student->age ?? 'N/A'}})</li>
                            </ul>
                            
                            <hr>
                            <h5 class="mb-10"><strong>Guardian Context</strong></h5>
                            <ul class="list-unstyled">
                                @if($student->parents)
                                    <li><strong>Father:</strong> {{$student->parents->fathers_name ?? 'N/A'}} ({{$student->parents->fathers_mobile ?? 'N/A'}})</li>
                                    <li><strong>Mother:</strong> {{$student->parents->mothers_name ?? 'N/A'}} ({{$student->parents->mothers_mobile ?? 'N/A'}})</li>
                                    <li><strong>Guardian:</strong> {{$student->parents->guardians_name ?? 'N/A'}} ({{$student->parents->guardians_mobile ?? 'N/A'}})</li>
                                @else
                                    <li><em>No Parent Record Found</em></li>
                                @endif
                            </ul>
                            
                            <hr>
                            <div class="text-center mt-20">
                                <a href="{{route('student_view', [$student->id])}}" class="primary-btn small fix-gr-bg">
                                    <span class="ti-eye"></span> Full Profile Timeline
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @elseif(isset($students))
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="white-box">
                    <h3 class="text-center text-danger">No student found!</h3>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
