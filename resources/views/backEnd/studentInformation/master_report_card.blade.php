@extends('backEnd.master')
@section('title') 
@lang('reports.master_report_card')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.master_report_card')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.master_report_card')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="white-box">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-15">@lang('common.select_criteria')</h3>
                    </div>
                </div>
            </div>
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_master_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
            <div class="row">
                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                <div class="col-lg-4 mt-30-md">
                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                        @foreach($classes as $class)
                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('class'))
                    <span class="text-danger invalid-select" role="alert">
                        {{ $errors->first('class') }}
                    </span>
                    @endif
                </div>

                <div class="col-lg-4 mt-30-md" id="select_section_div">
                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                        <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                        @if(isset($section_id))
                        <option value="{{$section_id}}" selected>{{App\SmSection::find($section_id)->section_name}}</option>
                        @endif
                    </select>
                    <div class="pull-right loader loader_style" id="select_section_loader">
                        <img class="loader_img_style" src="{{asset('backEnd/img/demo_wait.gif')}}" alt="loader">
                    </div>
                    @if ($errors->has('section'))
                    <span class="text-danger invalid-select" role="alert">
                        {{ $errors->first('section') }}
                    </span>
                    @endif
                </div>

                <div class="col-lg-4 mt-30-md" id="select_student_div">
                    <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="select_student" name="student">
                        <option data-display="@lang('common.select_student') *" value="">@lang('common.select_student') *</option>
                        @if(isset($student_id))
                        <option value="{{$student_id}}" selected>{{App\SmStudent::find($student_id)->full_name}}</option>
                        @endif
                    </select>
                    <div class="pull-right loader loader_style" id="select_student_loader">
                        <img class="loader_img_style" src="{{asset('backEnd/img/demo_wait.gif')}}" alt="loader">
                    </div>
                    @if ($errors->has('student'))
                    <span class="text-danger invalid-select" role="alert">
                        {{ $errors->first('student') }}
                    </span>
                    @endif
                </div>

                <div class="col-lg-12 mt-20 text-right">
                    <button type="submit" class="primary-btn small fix-gr-bg">
                        <span class="ti-search pr-2"></span>
                        @lang('common.search')
                    </button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</section>

@if(isset($student))
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-3">
                <div class="student-meta-box">
                    <div class="white-box p-3 radius-10">
                        <div class="single-meta">
                            <h5 class="name">Student Name</h5>
                            <div class="value">{{$student->full_name}}</div>
                        </div>
                        <div class="single-meta mt-10">
                            <h5 class="name">Admission No</h5>
                            <div class="value">{{$student->admission_no}}</div>
                        </div>
                        <div class="single-meta mt-10">
                            <h5 class="name">EMIS ID</h5>
                            <div class="value">{{$student->emis_id ?? 'N/A'}}</div>
                        </div>
                        <div class="single-meta mt-10">
                            <h5 class="name">Gender</h5>
                            <div class="value">{{$student->gender ? $student->gender->base_setup_name : ''}}</div>
                        </div>
                        <div class="single-meta mt-10">
                            <h5 class="name">Blood Group</h5>
                            <div class="value">{{$student->bloodGroup ? $student->bloodGroup->base_setup_name : ''}}</div>
                        </div>
                        <div class="single-meta mt-10">
                            <h5 class="name">Parent</h5>
                            <div class="value">{{$student->parents ? $student->parents->guardians_name : ''}} <br> {{$student->parents ? $student->parents->guardians_mobile : ''}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <!-- Attendance -->
                    <div class="col-lg-6 mb-30">
                        <div class="white-box p-3 radius-10 h-100">
                            <h4>Attendance Summary</h4>
                            <ul class="list-group mt-15">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Present
                                    <span class="badge badge-success badge-pill">{{$attendances['P'] ?? 0}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Late
                                    <span class="badge badge-warning badge-pill">{{$attendances['L'] ?? 0}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Absent
                                    <span class="badge badge-danger badge-pill">{{$attendances['A'] ?? 0}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Half Day
                                    <span class="badge badge-info badge-pill">{{$attendances['F'] ?? 0}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Financial -->
                    <div class="col-lg-6 mb-30">
                        <div class="white-box p-3 radius-10 h-100">
                            <h4>Financial Snapshot</h4>
                            <ul class="list-group mt-15">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Fees
                                    <span>{{generalSetting()->currency_symbol}}{{number_format($financial['total_fees'], 2)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Discount
                                    <span>{{generalSetting()->currency_symbol}}{{number_format($financial['total_discount'], 2)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Paid
                                    <span class="text-success">{{generalSetting()->currency_symbol}}{{number_format($financial['total_paid'], 2)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    Balance Due
                                    <span class="text-danger">{{generalSetting()->currency_symbol}}{{number_format($financial['total_due'], 2)}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Marks -->
                    <div class="col-lg-12">
                        <div class="white-box p-3 radius-10">
                            <h4>Academic Performance (Exams)</h4>
                            <div class="table-responsive mt-15">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Exam</th>
                                            <th>Subject</th>
                                            <th>Marks</th>
                                            <th>GPA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($marks as $mark)
                                        <tr>
                                            <td>{{$mark->exam ? $mark->exam->title : 'N/A'}}</td>
                                            <td>{{$mark->subject ? $mark->subject->subject_name : 'N/A'}}</td>
                                            <td>{{$mark->total_marks}}</td>
                                            <td>{{$mark->total_gpa_grade}}</td>
                                        </tr>
                                        @endforeach
                                        @if(count($marks) == 0)
                                        <tr>
                                            <td colspan="4" class="text-center">No exam marks found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
