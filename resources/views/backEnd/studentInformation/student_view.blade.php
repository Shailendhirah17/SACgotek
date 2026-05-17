@extends('backEnd.master')
@push('css')
    <style>
        .school-table-up-style tr td {
            padding: 8px 6px 8px 10px !important;
            font-size: 12px !important;
        }

        .school-table-style {
            padding: 0px !important;
        }

        .badge {
            background: var(--primary-color);
            color: #fff;
            padding: 5px 10px;
            border-radius: 30px;
            display: inline-block;
            font-size: 8px;
        }

        table.dataTable thead th {
            padding-left: 25px !important;
        }

        table.dataTable thead th::after {
            left: 10px !important;
            top: 10px !important;
        }

        table.dataTable tbody td {
            padding-left: 13px !important;
        }

        .school-table-style tr th {
            padding: 10px 18px 10px 10px !important;
        }

        .school-table-style tr td {
            padding: 20px 10px 20px 10px !important;
        }

        .input-right-icon button.primary-btn-small-input {
            top: 8px !important;
            right: 11px !important;
        }

        .table thead th {
            font-size: 12px !important;
        }
        
        /* Scoped custom dashboard styling */
        .custom-dashboard-wrapper {
            --bg-dash: #0f111a;
            --card-dash: #161824;
            --card-dash2: #1f2235;
            --accent-dash: #6c5ce7;
            --accent-dash2: #a29bfe;
            --green-dash: #00b894;
            --red-dash: #e17055;
            --orange-dash: #fdcb6e;
            --blue-dash: #74b9ff;
            --text-dash: #f3f4f6;
            --text-dash2: #9ca3af;
            --border-dash: #2d3142;
            --shadow-dash: 0 10px 30px rgba(0, 0, 0, 0.4);
            --radius-dash: 16px;
            font-family: 'Inter', sans-serif;
            color: var(--text-dash);
        }

        .custom-dashboard-wrapper .glass-card {
            background: var(--card-dash) !important;
            border: 1px solid var(--border-dash) !important;
            border-radius: var(--radius-dash) !important;
            padding: 24px !important;
            margin-bottom: 24px !important;
            box-shadow: var(--shadow-dash) !important;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .custom-dashboard-wrapper .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.5);
            border-color: rgba(108, 92, 231, 0.5) !important;
        }

        .custom-dashboard-wrapper .glass-card h3 {
            font-size: 18px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            margin-bottom: 20px !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            border-bottom: 1px solid rgba(45, 49, 66, 0.5) !important;
            padding-bottom: 12px !important;
        }

        .custom-dashboard-wrapper .stats-grid-dash {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
            gap: 20px !important;
            margin-bottom: 24px !important;
        }

        .custom-dashboard-wrapper .stat-card-dash {
            background: var(--card-dash2) !important;
            border: 1px solid var(--border-dash) !important;
            border-radius: 12px !important;
            padding: 16px 20px !important;
            display: flex !important;
            flex-direction: column !important;
            position: relative !important;
        }

        .custom-dashboard-wrapper .stat-card-dash .stat-value {
            font-size: 32px !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            margin-bottom: 4px !important;
        }

        .custom-dashboard-wrapper .stat-card-dash .stat-label {
            font-size: 13px !important;
            color: var(--text-dash2) !important;
            font-weight: 500 !important;
        }

        .custom-dashboard-wrapper .stat-card-dash .stat-icon {
            position: absolute !important;
            right: 20px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 28px !important;
            opacity: 0.15 !important;
        }

        .custom-dashboard-wrapper .grid-2-dash {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)) !important;
            gap: 24px !important;
        }

        .custom-dashboard-wrapper .grid-3-dash {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
            gap: 20px !important;
        }

        /* Badges */
        .custom-dashboard-wrapper .badge-dash {
            padding: 4px 10px !important;
            border-radius: 6px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            display: inline-block !important;
            line-height: 1.2 !important;
            text-transform: capitalize !important;
        }

        .custom-dashboard-wrapper .badge-good {
            background: rgba(0, 184, 148, 0.15) !important;
            color: var(--green-dash) !important;
        }

        .custom-dashboard-wrapper .badge-average {
            background: rgba(253, 203, 110, 0.15) !important;
            color: var(--orange-dash) !important;
        }

        .custom-dashboard-wrapper .badge-misbehavior {
            background: rgba(225, 112, 85, 0.15) !important;
            color: var(--red-dash) !important;
        }

        .custom-dashboard-wrapper .badge-prize {
            background: rgba(108, 92, 231, 0.15) !important;
            color: var(--accent-dash2) !important;
        }

        .custom-dashboard-wrapper .badge-participated {
            background: rgba(116, 185, 255, 0.15) !important;
            color: var(--blue-dash) !important;
        }

        .custom-dashboard-wrapper .badge-interested {
            background: rgba(156, 163, 175, 0.15) !important;
            color: var(--text-dash2) !important;
        }
        
        .custom-dashboard-wrapper .badge-sports {
            background: rgba(0, 184, 148, 0.15) !important;
            color: var(--green-dash) !important;
        }
        
        .custom-dashboard-wrapper .badge-extracurricular {
            background: rgba(108, 92, 231, 0.15) !important;
            color: var(--accent-dash2) !important;
        }
        
        .custom-dashboard-wrapper .badge-academic {
            background: rgba(116, 185, 255, 0.15) !important;
            color: var(--blue-dash) !important;
        }

        /* Timeline */
        .custom-dashboard-wrapper .timeline-dash {
            border-left: 2px solid var(--border-dash) !important;
            padding-left: 20px !important;
            margin-left: 10px !important;
            position: relative !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash {
            position: relative !important;
            margin-bottom: 24px !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash::before {
            content: '' !important;
            position: absolute !important;
            left: -26px !important;
            top: 4px !important;
            width: 10px !important;
            height: 10px !important;
            border-radius: 50% !important;
            background: var(--accent-dash) !important;
            border: 2px solid var(--card-dash) !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash.good::before {
            background: var(--green-dash) !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash.average::before {
            background: var(--orange-dash) !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash.misbehavior::before {
            background: var(--red-dash) !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash .time-dash {
            font-size: 11px !important;
            color: var(--text-dash2) !important;
            margin-bottom: 4px !important;
            font-weight: 500 !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash .title-dash {
            font-size: 14px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            margin-bottom: 4px !important;
        }

        .custom-dashboard-wrapper .timeline-item-dash .desc-dash {
            font-size: 13px !important;
            color: var(--text-dash2) !important;
        }

        /* Form Styles */
        .custom-dashboard-wrapper .form-glass {
            background: rgba(22, 24, 36, 0.4) !important;
            border: 1px solid rgba(45, 49, 66, 0.8) !important;
            border-radius: 12px !important;
            padding: 20px !important;
        }

        .custom-dashboard-wrapper .form-group-dash {
            margin-bottom: 16px !important;
        }

        .custom-dashboard-wrapper .form-group-dash label {
            display: block !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: var(--text-dash2) !important;
            margin-bottom: 6px !important;
            text-transform: uppercase !important;
        }

        .custom-dashboard-wrapper .form-group-dash input,
        .custom-dashboard-wrapper .form-group-dash select,
        .custom-dashboard-wrapper .form-group-dash textarea {
            width: 100% !important;
            padding: 10px 14px !important;
            background: var(--card-dash2) !important;
            border: 1px solid var(--border-dash) !important;
            border-radius: 8px !important;
            color: #ffffff !important;
            font-size: 13px !important;
            outline: none !important;
        }

        .custom-dashboard-wrapper .form-group-dash input:focus,
        .custom-dashboard-wrapper .form-group-dash select:focus,
        .custom-dashboard-wrapper .form-group-dash textarea:focus {
            border-color: var(--accent-dash) !important;
            box-shadow: 0 0 0 2px rgba(108, 92, 231, 0.2) !important;
        }

        .custom-dashboard-wrapper .btn-dash {
            width: 100% !important;
            padding: 12px !important;
            border-radius: 8px !important;
            border: none !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        .custom-dashboard-wrapper .btn-dash-primary {
            background: linear-gradient(135deg, var(--accent-dash), #8b5cf6) !important;
            color: #ffffff !important;
        }

        .custom-dashboard-wrapper .btn-dash-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4) !important;
        }

        /* Engagement score ring widget */
        .custom-dashboard-wrapper .score-ring-container {
            display: flex !important;
            align-items: center !important;
            gap: 20px !important;
        }

        .custom-dashboard-wrapper .score-ring {
            width: 80px !important;
            height: 80px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 24px !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.5) !important;
        }

        .custom-dashboard-wrapper .score-ring.score-high {
            border: 6px solid var(--green-dash) !important;
            background: rgba(0, 184, 148, 0.1) !important;
        }

        .custom-dashboard-wrapper .score-ring.score-mid {
            border: 6px solid var(--orange-dash) !important;
            background: rgba(253, 203, 110, 0.1) !important;
        }

        .custom-dashboard-wrapper .score-ring.score-low {
            border: 6px solid var(--red-dash) !important;
            background: rgba(225, 112, 85, 0.1) !important;
        }

        /* Progress bar inside custom dashboard */
        .custom-dashboard-wrapper .progress-bar-dash {
            background: var(--card-dash2) !important;
            border-radius: 8px !important;
            height: 10px !important;
            overflow: hidden !important;
            margin-top: 8px !important;
        }

        .custom-dashboard-wrapper .progress-fill-dash {
            height: 100% !important;
            border-radius: 8px !important;
            transition: width 0.6s ease !important;
        }

        .custom-dashboard-wrapper .progress-fill-dash.purple {
            background: linear-gradient(90deg, var(--accent-dash), var(--accent-dash2)) !important;
        }

        .custom-dashboard-wrapper .progress-fill-dash.green {
            background: linear-gradient(90deg, #00b894, #55efc4) !important;
        }

        /* Library and Fees list styling */
        .custom-dashboard-wrapper .spending-item {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 12px !important;
            background: var(--card-dash2) !important;
            border-radius: 8px !important;
            margin-bottom: 10px !important;
            border-left: 3px solid var(--accent-dash) !important;
        }

        .custom-dashboard-wrapper .spending-title {
            font-weight: 600 !important;
            font-size: 13px !important;
        }

        .custom-dashboard-wrapper .spending-desc {
            font-size: 11px !important;
            color: var(--text-dash2) !important;
        }

        .custom-dashboard-wrapper .spending-amount {
            font-size: 15px !important;
            font-weight: 800 !important;
            color: #ffffff !important;
        }

        .custom-dashboard-wrapper .table-wrap-dash {
            overflow-x: auto !important;
        }

        .custom-dashboard-wrapper table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .custom-dashboard-wrapper th {
            text-align: left !important;
            padding: 12px 16px !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            color: var(--text-dash2) !important;
            border-bottom: 1px solid var(--border-dash) !important;
            font-weight: 600 !important;
        }

        .custom-dashboard-wrapper td {
            padding: 12px 16px !important;
            font-size: 13px !important;
            border-bottom: 1px solid rgba(45, 49, 66, 0.4) !important;
            color: var(--text-dash) !important;
        }

        .custom-dashboard-wrapper tr:hover td {
            background: rgba(108, 92, 231, 0.05) !important;
        }
    </style>
@endpush

@section('title')
    @lang('student.student_details')
@endsection

@section('mainContent')


    @php
        $setting = app('school_info');
        if (!empty($setting->currency_symbol)) {
            $currency = $setting->currency_symbol;
        } else {
            $currency = '$';
        }
    @endphp

    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('student.student_details')</h1>
                <div class="bc-pages">
                    <a href="{{ url('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="{{ route('student_list') }}">@lang('student.student_list')</a>
                    <a href="#">@lang('student.student_details')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">

                    @if (moduleStatusCheck('University'))
                        @includeIf('university::promote.inc.student_profile', [
                            'student_detail' => $student_detail->defaultClass,
                            'student' => $student_detail,
                        ])
                    @else
                        @includeIf('backEnd.studentInformation.inc.student_profile')
                    @endif

                </div>

                @php
                    $type = isset($type) ? $type : null;
                    
                    $student_id = $student_detail->id;
                    $user_id = $student_detail->user_id;

                    // Fetch Behavior Tracking
                    $behaviors = DB::table('sm_student_behaviors')
                        ->where('student_id', $student_id)
                        ->orderBy('reported_date', 'desc')
                        ->get();

                    // Fetch Activities & Interests
                    $activities = DB::table('sm_student_activities')
                        ->where('student_id', $student_id)
                        ->orderBy('activity_name', 'asc')
                        ->get();

                    // Fetch Achievements
                    $achievements = DB::table('sm_student_achievements')
                        ->where('student_id', $student_id)
                        ->orderBy('achievement_date', 'desc')
                        ->get();

                    // Fetch Spending
                    $spending = DB::table('sm_student_spending')
                        ->where('student_id', $student_id)
                        ->orderBy('spending_date', 'desc')
                        ->get();
                    $total_spending = $spending->sum('amount');

                    // Fetch Communications
                    $comms = DB::table('sm_student_communications')
                        ->where('student_id', $student_id)
                        ->orWhereNull('student_id')
                        ->orderBy('sent_at', 'desc')
                        ->take(15)
                        ->get();

                    // Fetch Library Issues (linked to user_id via library membership)
                    $library_issues = DB::table('sm_book_issues as bi')
                        ->select('bi.*', 'b.book_title', 'b.author_name', 'b.isbn_no')
                        ->join('sm_books as b', 'bi.book_id', '=', 'b.id')
                        ->join('sm_library_members as lm', 'bi.member_id', '=', 'lm.id')
                        ->where('lm.student_staff_id', $user_id)
                        ->orderBy('bi.given_date', 'desc')
                        ->get();

                    // Calculate Engagement Score
                    $present_count = DB::table('sm_student_attendances')
                        ->where('student_id', $student_id)
                        ->where('attendance_type', 'P')
                        ->count();

                    $attendance_total = DB::table('sm_student_attendances')
                        ->where('student_id', $student_id)
                        ->count();

                    $behavior_count = $behaviors->count();
                    $activity_count = $activities->count();
                    $achievement_count = $achievements->count();

                    $engagement_score = ($behavior_count * 10) + ($activity_count * 15) + ($achievement_count * 25) + ($present_count * 2);
                    if ($engagement_score > 100) $engagement_score = 100; // Cap at 100

                    $engagement_status = $engagement_score >= 50 ? 'Highly Active' : ($engagement_score >= 20 ? 'Active' : 'Inactive');
                    $engagement_class = $engagement_score >= 50 ? 'badge-good' : ($engagement_score >= 20 ? 'badge-average' : 'badge-misbehavior');
                @endphp

                <!-- Start Student Details -->
                <div class="col-lg-9 student-details up_admin_visitor">
                    <div class="white-box">
                        <ul class="nav nav-tabs tabs_scroll_nav mb-10" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $type == '' && Session::get('studentDocuments') == '' ? 'active' : '' }} "
                                    href="#studentProfile" role="tab" data-toggle="tab">@lang('student.profile')</a>
                            </li>
    
                            @if (generalSetting()->fees_status == 0)
                                <li class="nav-item">
                                    <a class="nav-link" href="#studentFees" role="tab"
                                        data-toggle="tab">@lang('fees.fees')</a>
                                </li>
                            @endif
                            @if (isMenuAllowToShow('leave'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#leaves" role="tab" data-toggle="tab">@lang('leave.leave')</a>
                                </li>
                            @endif
                            @if (isMenuAllowToShow('examination'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#studentExam" role="tab"
                                        data-toggle="tab">@lang('exam.exam')</a>
                                </li>
                            @endif
                            @if (moduleStatusCheck('University'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#studentExamTranscript" role="tab"
                                        data-toggle="tab">@lang('university::un.transcript')</a>
                                </li>
                            @endif
    
                            <li class="nav-item">
                                <a class="nav-link {{ Session::get('studentDocuments') == 'active' ? 'active' : '' }}"
                                    href="#studentDocuments" role="tab" data-toggle="tab">@lang('student.document')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Session::get('studentRecord') == 'active' ? 'active' : '' }} "
                                    href="#studentRecord" role="tab" data-toggle="tab">@lang('student.record')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $type == 'studentTimeline' ? 'active' : '' }} " href="#studentTimeline"
                                    role="tab" data-toggle="tab">@lang('student.timeline')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Session::get('studentAttendance') == 'active' ? 'active' : '' }} "
                                    href="#studentAttendance" role="tab" data-toggle="tab">@lang('student.student_attendance')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Session::get('subjectAttendance') == 'active' ? 'active' : '' }} "
                                    href="#subjectAttendance" role="tab" data-toggle="tab">@lang('student.subject_attendance')</a>
                            </li>
                            @if (moduleStatusCheck('BehaviourRecords'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Session::get('studentBehaviourRecord') == 'active' ? 'active' : '' }} "
                                        href="#studentBehaviourRecord" role="tab" data-toggle="tab">@lang('student.behaviour_record')</a>
                                </li>
                            @endif
                            @if (generalSetting()->result_type == 'mark')
                                <li class="nav-item">
                                    <a class="nav-link {{ $type == 'mark' ? 'active' : '' }} " href="#mark" role="tab"
                                        data-toggle="tab">@lang('exam.marksheet')</a>
                                </li>
                            @endif
    
                            @if (moduleStatusCheck('University'))
                                <li class="nav-item">
                                    <a class="nav-link {{ $type == 'assign_subject' ? 'active' : '' }} " href="#studentSubject"
                                        role="tab" data-toggle="tab">@lang('university::un.subject')</a>
                                </li>
                            @endif
    
                            <li class="nav-item">
                                <a class="nav-link" href="#customBehavior" role="tab" data-toggle="tab"><i class="fas fa-heart mr-2"></i>Behavior Tracking</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#customActivities" role="tab" data-toggle="tab"><i class="fas fa-futbol mr-2"></i>Activities & Interests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#customAchievements" role="tab" data-toggle="tab"><i class="fas fa-trophy mr-2"></i>Achievements & Engagement</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#customLibrary" role="tab" data-toggle="tab"><i class="fas fa-book mr-2"></i>Library Tracker</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#customSpending" role="tab" data-toggle="tab"><i class="fas fa-wallet mr-2"></i>Fee Spending & Canteen</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#customComms" role="tab" data-toggle="tab"><i class="fas fa-envelope mr-2"></i>Communication Log</a>
                            </li>
                            
                            <li class="nav-item edit-button">
                                @if (userPermission('student_edit'))
                                    <a href="{{ route('student_edit', [@$student_detail->id]) }}"
                                        class="primary-btn small fix-gr-bg">@lang('common.edit')
                                    </a>
                                @endif
                            </li>
                        </ul>
    
    
                        <!-- Tab panes -->
                        <div class="tab-content">
    
                            <!-- Start Profile Tab -->
                            @include('backEnd.studentInformation.inc._profile_tab')
                            <!-- End Profile Tab -->
    
                            <!-- Start Fees Tab -->
                            @include('backEnd.studentInformation.inc._fees_tab')
                            <!-- End Fees Tab -->
    
                            <!-- Start leave Tab -->
                            @include('backEnd.studentInformation.inc._leave_tab')
                            <!-- End leave Tab -->
    
                            <!-- Start Exam Tab -->
                            @include('backEnd.studentInformation.inc._exam_tab')
                            <!-- End Exam Tab -->
    
                            @if (moduleStatusCheck('University'))
                                <div role="tabpanel" class="tab-pane fade" id="studentExamTranscript">
                                    @includeIf('university::exam.partials._examTabView')
                                </div>
                            @endif
    
                            <!-- Start Documents Tab -->
                            @include('backEnd.studentInformation.inc._document_tab')
                            <!-- End Documents Tab -->
    
                            <!-- Start reocrd Tab -->
                            <div role="tabpanel"
                                class="tab-pane fade {{ Session::get('studentRecord') == 'active' ? 'show active' : '' }}"
                                id="studentRecord">
                                <div>
                                    <div class="text-right mb-20">
                                        @if (userPermission(1201))
                                            <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button"
                                                data-toggle="modal" data-target="#assignClass"> <span
                                                    class="ti-plus pr-2"></span> @lang('common.add')</button>
                                        @endif
                                    </div>
                                    <table id="" class="table simple-table table-responsive school-table"
                                        cellspacing="0">
                                        <thead class="d-block">
                                            <tr class="d-flex">
                                                @if (moduleStatusCheck('University'))
                                                    <th class="col-2">@lang('university::un.session')</th>
                                                    <th class="col-3">@lang('university::un.faculty_department')</th>
                                                    <th class="col-3">@lang('university::un.semester(label)')</th>
                                                @else
                                                    <th class="col-3">@lang('common.class')</th>
                                                    <th class="col-3">@lang('common.section')</th>
                                                    @if(shiftEnable())
                                                    <th class="col-3">@lang('common.shift')</th>
                                                    @endif
                                                @endif
                                                @if ($setting->multiple_roll == 1)
                                                    <th class="col-2">@lang('student.id_number')</th>
                                                @endif
                                                <th class="col-{{$setting->multiple_roll == 1 ? 2 : 4}}" style="text-align: center">@lang('student.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="d-block">
                                            @foreach ($records->where('active_status', 1) as $record)
                                                <tr class="d-flex">
                                                    @if (moduleStatusCheck('University'))
                                                        <td class="col-2">{{ $record->unSession->name }}</td>
                                                        <td class="col-3">
                                                            {{ $record->unFaculty->name . '(' . $record->unDepartment->name . ')' }}
                                                            @if ($record->is_default)
                                                                <span
                                                                    class="badge fix-gr-bg">{{ __('common.default') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="col-3">
                                                            {{ $record->unSemester->name . '(' . $record->unSemesterLabel->name . ')' }}
                                                        </td>
                                                    @else
                                                        <td class="col-3">
                                                            {{ $record->class->class_name }}
                                                            @if ($record->is_default)
                                                                <span
                                                                    class="badge fix-gr-bg">{{ __('common.default') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="col-3">
                                                            {{ $record->section->section_name }}
                                                        </td>
                                                        @if(shiftEnable() && $record->shift)
                                                        <td class="col-3">
                                                            {{ $record->shift->name }}
                                                        </td>
                                                        @endif
                                                    @endif
    
                                                    @if ($setting->multiple_roll == 1)
                                                        <td class="col-2">{{ $record->roll_no }}</td>
                                                    @endif
                                                    <td class="col-{{$setting->multiple_roll == 1 ? 2 : 4}}" style="text-align: center">
                                                        @if ($record->is_promote == 0)
                                                            <a class="primary-btn icon-only fix-gr-bg modalLink"
                                                                data-modal-size="small-modal"
                                                                title="@if (moduleStatusCheck('University')) @lang('university::un.assign_faculty_department')
                                                                    @else 
                                                                        @lang('student.assign_class') @endif"
                                                                href="{{ route('student_assign_edit', [@$record->student_id, @$record->id]) }}"><span
                                                                    class="ti-pencil"></span></a>
                                                            <a href="#" class="primary-btn icon-only fix-gr-bg"
                                                                data-toggle="modal"
                                                                data-target="#deleteRecord_{{ $record->id }}">
                                                                <span class="ti-trash"></span>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <div class="modal fade admin-query" id="deleteRecord_{{ $record->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('common.delete')</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <form action="{{ route('student.record.delete') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="text-center">
                                                                        <h4>@lang('student.Are you sure you want to move the following record to the trash?')</h4>
                                                                    </div>
                                                                    <input type="checkbox" id="record{{ @$record->id }}"
                                                                        class="common-checkbox form-control{{ @$errors->has('record') ? ' is-invalid' : '' }}"
                                                                        name="type">
                                                                    <label
                                                                        for="record{{ @$record->id }}">{{ __('student.Skip the trash and permanently delete the record') }}</label>
                                                                    <input type="hidden" name="student_id"
                                                                        value="{{ $record->student_id }}">
                                                                    <input type="hidden" name="record_id"
                                                                        value="{{ $record->id }}">
                                                                    <div class="mt-40 d-flex justify-content-between">
                                                                        <button type="button" class="primary-btn tr-bg"
                                                                            data-dismiss="modal">@lang('common.cancel')</button>
                                                                        <button type="submit"
                                                                            class="primary-btn fix-gr-bg">@lang('common.delete')</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Record delete --}}
                                                {{-- edit record --}}
                                            @endforeach
                                            {{-- end edit record --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- End reocrd Tab -->
    
                            <!-- Start Timeline Tab -->
                            <div role="tabpanel" class="tab-pane fade" id="studentTimeline">
                                <div>
                                    <div class="text-right mb-20">
                                        <button type="button" data-toggle="modal" data-target="#add_timeline_madal"
                                            class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('common.add')
                                            <span class="pl ti-plus"></span>
                                        </button>
                                    </div>
                                    @foreach ($timelines as $timeline)
                                        <div class="student-activities">
                                            <div class="single-activity">
                                                <h4 class="title text-uppercase">
                                                    {{ $timeline->date != '' ? dateConvert($timeline->date) : '' }}</h4>
                                                <div class="sub-activity-box d-flex">
                                                    <h6 class="time text-uppercase">10.30 pm</h6>
                                                    <div class="sub-activity">
                                                        <h5 class="subtitle text-uppercase"> {{ $timeline->title }}</h5>
                                                        <p>
                                                            {{ $timeline->description }}
                                                        </p>
                                                    </div>
    
                                                    <div class="close-activity">
    
                                                        <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                                            data-target="#deleteTimelineModal{{ $timeline->id }}"
                                                            href="#">
                                                            <span class="ti-trash text-white"></span>
                                                        </a>
    
                                                        @if (file_exists($timeline->file))
                                                            <a href="{{ url($timeline->file) }}"
                                                                class="primary-btn tr-bg text-uppercase bord-rad" download>
                                                                @lang('common.download')<span class="pl ti-download"></span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade admin-query" id="deleteTimelineModal{{ $timeline->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('common.delete')</h4>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>
    
                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')
                                                                </button>
                                                                <a class="primary-btn fix-gr-bg"
                                                                    href="{{ route('delete_timeline', [$timeline->id]) }}">
                                                                    @lang('common.delete')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- End Timeline Tab -->
    
                            <!-- Start Attendance Tab -->
                            @include('backEnd.studentInformation.inc._student_attendance_tab')
                            <!-- End Attendance Tab -->
    
                            <!-- Start Attendance Tab -->
                            @include('backEnd.studentInformation.inc._subject_attendance_tab')
                            <!-- End Attendance Tab -->
    
                            <!-- Start Behaviour Records Tab -->
                            @if (moduleStatusCheck('BehaviourRecords'))
                                @include('backEnd.studentInformation.inc._student_behaviour_record_tab')
                            @endif
                            <!-- End Behaviour Records Tab -->
                            {{-- start marksheet tab  --}}
                            @if (generalSetting()->result_type == 'mark')
                                <div role="tabpanel"
                                    class="tab-pane fade {{ Session::get('mark') == 'active' ? 'show active' : '' }}"
                                    id="mark">
                                    <div class="white-box">
                                        @foreach ($records as $record)
                                            @includeIf('backEnd.studentInformation.inc.finalMarkSheet')
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if (moduleStatusCheck('University'))
                                <div role="tabpanel" class="tab-pane fade {{ $type == 'assign_subject' ? ' active show' : '' }}" id="studentSubject">
                                    @include('backEnd.studentInformation.inc.subject_list')
                                </div>
                            @endif
                            {{-- end marksheet tab  --}}
                            
                            <!-- Custom Behavior Tab Pane -->
                            <div role="tabpanel" class="tab-pane fade" id="customBehavior">
                                <div class="custom-dashboard-wrapper">
                                    <div class="stats-grid-dash">
                                        <div class="stat-card-dash">
                                            <span class="stat-value">{{ $behaviors->where('behavior_type', 'good')->count() }}</span>
                                            <span class="stat-label">Good Behaviors</span>
                                            <i class="fas fa-smile stat-icon" style="color: var(--green-dash)"></i>
                                        </div>
                                        <div class="stat-card-dash">
                                            <span class="stat-value">{{ $behaviors->where('behavior_type', 'average')->count() }}</span>
                                            <span class="stat-label">Average Behaviors</span>
                                            <i class="fas fa-meh stat-icon" style="color: var(--orange-dash)"></i>
                                        </div>
                                        <div class="stat-card-dash">
                                            <span class="stat-value">{{ $behaviors->where('behavior_type', 'misbehavior')->count() }}</span>
                                            <span class="stat-label">Misbehaviors</span>
                                            <i class="fas fa-frown stat-icon" style="color: var(--red-dash)"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-heart text-danger"></i> Behavior Logs & Remarks</h3>
                                                @if($behaviors->count() > 0)
                                                    <div class="timeline-dash">
                                                        @foreach($behaviors as $b)
                                                            <div class="timeline-item-dash {{ $b->behavior_type }}">
                                                                <div class="time-dash"><i class="far fa-calendar-alt"></i> {{ dateConvert($b->reported_date) }}</div>
                                                                <div class="title-dash">
                                                                    <span class="badge-dash badge-{{ $b->behavior_type }}">{{ ucfirst($b->behavior_type) }}</span> 
                                                                    - {{ ucfirst($b->category) }}
                                                                </div>
                                                                <div class="desc-dash">
                                                                    <strong>Remarks:</strong> {{ $b->remarks }}<br>
                                                                    <small class="text-muted">Reported By: {{ $b->reported_by }}</small>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                                        <p class="text-muted">No behavior records logged yet.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-5">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-plus-circle text-primary"></i> Log New Behavior</h3>
                                                <form id="behaviorForm" class="form-glass" onsubmit="submitCustomForm(event, 'behaviors', 'behaviorForm')">
                                                    <div class="form-group-dash">
                                                        <label>Behavior Type</label>
                                                        <select name="behavior_type" required>
                                                            <option value="good">Good</option>
                                                            <option value="average">Average</option>
                                                            <option value="misbehavior">Misbehavior</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Category</label>
                                                        <select name="category" required>
                                                            <option value="discipline">Discipline</option>
                                                            <option value="attitude">Attitude</option>
                                                            <option value="conduct">Conduct</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Remarks</label>
                                                        <textarea name="remarks" rows="4" placeholder="Enter behavioral remarks..." required></textarea>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Reported By</label>
                                                        <input type="text" name="reported_by" value="{{ Auth::user()->full_name ?? 'Admin' }}" required>
                                                    </div>
                                                    <button type="submit" class="btn-dash btn-dash-primary">
                                                        <i class="fas fa-save"></i> Save Behavior Record
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Custom Activities Tab Pane -->
                            <div role="tabpanel" class="tab-pane fade" id="customActivities">
                                <div class="custom-dashboard-wrapper">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-futbol text-success"></i> Sports & Extracurricular Activities</h3>
                                                @if($activities->count() > 0)
                                                    <div class="grid-2-dash">
                                                        @foreach($activities as $act)
                                                            <div class="stat-card-dash" style="border-left: 4px solid var(--accent-dash)">
                                                                <span class="stat-value" style="font-size: 18px; margin-bottom: 8px;">
                                                                    {{ $act->activity_name }}
                                                                </span>
                                                                <span class="stat-label mb-2">
                                                                    <span class="badge-dash badge-{{ $act->activity_type }}">{{ ucfirst($act->activity_type) }}</span>
                                                                    <span class="badge-dash badge-prize ml-1">{{ ucfirst($act->skill_level) }}</span>
                                                                </span>
                                                                @if($act->notes)
                                                                    <p class="text-muted" style="font-size: 12px; margin: 0; font-style: italic">
                                                                        "{{ $act->notes }}"
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                                        <p class="text-muted">No activities or interests registered.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-5">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-plus-circle text-primary"></i> Add Activity / Interest</h3>
                                                <form id="activityForm" class="form-glass" onsubmit="submitCustomForm(event, 'activities', 'activityForm')">
                                                    <div class="form-group-dash">
                                                        <label>Activity Type</label>
                                                        <select name="activity_type" required>
                                                            <option value="sports">Sports</option>
                                                            <option value="extracurricular">Extracurricular</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Activity Name</label>
                                                        <input type="text" name="activity_name" placeholder="e.g. Cricket, Dance, Coding, Music" required>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Skill Level</label>
                                                        <select name="skill_level" required>
                                                            <option value="beginner">Beginner</option>
                                                            <option value="intermediate">Intermediate</option>
                                                            <option value="advanced">Advanced</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Notes / Goals</label>
                                                        <textarea name="notes" rows="3" placeholder="Enter student goals or coach notes..."></textarea>
                                                    </div>
                                                    <button type="submit" class="btn-dash btn-dash-primary">
                                                        <i class="fas fa-plus"></i> Add Activity Record
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Custom Achievements Tab Pane -->
                            <div role="tabpanel" class="tab-pane fade" id="customAchievements">
                                <div class="custom-dashboard-wrapper">
                                    <div class="row mb-4">
                                        <div class="col-lg-6">
                                            <div class="glass-card score-ring-container">
                                                <div class="score-ring {{ $engagement_class }}">
                                                    {{ $engagement_score }}
                                                </div>
                                                <div>
                                                    <h4 style="margin: 0 0 4px; font-weight: 700;">Student Engagement Score</h4>
                                                    <p class="text-muted" style="margin: 0; font-size: 13px;">
                                                        Status: <strong class="{{ $engagement_class }}">{{ $engagement_status }}</strong>
                                                    </p>
                                                    <div class="progress-bar-dash" style="width: 200px;">
                                                        <div class="progress-fill-dash green" style="width: {{ $engagement_score }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="glass-card">
                                                <h4 style="margin: 0 0 10px; font-weight: 700;"><i class="fas fa-chart-line text-info"></i> Performance Score Matrix</h4>
                                                <div style="font-size: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                                    <div><i class="fas fa-check-circle text-success"></i> Behavior Log Weight: <strong>+10 pts</strong></div>
                                                    <div><i class="fas fa-check-circle text-success"></i> Activities Weight: <strong>+15 pts</strong></div>
                                                    <div><i class="fas fa-check-circle text-success"></i> Achievements Weight: <strong>+25 pts</strong></div>
                                                    <div><i class="fas fa-check-circle text-success"></i> Attendance Present Days: <strong>+2 pts</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-trophy text-warning"></i> Key Achievements & Participation</h3>
                                                @if($achievements->count() > 0)
                                                    <div class="table-wrap-dash">
                                                        <table>
                                                            <thead>
                                                                <tr>
                                                                    <th>Title</th>
                                                                    <th>Type</th>
                                                                    <th>Status</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($achievements as $ach)
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $ach->title }}</strong>
                                                                            @if($ach->description)
                                                                                <div style="font-size: 11px; color: var(--text-dash2)">{{ $ach->description }}</div>
                                                                            @endif
                                                                        </td>
                                                                        <td><span class="badge-dash badge-{{ $ach->achievement_type }}">{{ ucfirst($ach->achievement_type) }}</span></td>
                                                                        <td>
                                                                            @php
                                                                                $status_badge = $ach->participation_status == 'prize_winner' ? 'badge-prize' : ($ach->participation_status == 'participated' ? 'badge-participated' : 'badge-interested');
                                                                            @endphp
                                                                            <span class="badge-dash {{ $status_badge }}">{{ ucwords(str_replace('_', ' ', $ach->participation_status)) }}</span>
                                                                        </td>
                                                                        <td>{{ dateConvert($ach->achievement_date) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                                        <p class="text-muted">No achievements logged yet.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-5">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-plus-circle text-primary"></i> Add Achievement</h3>
                                                <form id="achievementForm" class="form-glass" onsubmit="submitCustomForm(event, 'achievements', 'achievementForm')">
                                                    <div class="form-group-dash">
                                                        <label>Achievement Type</label>
                                                        <select name="achievement_type" required>
                                                            <option value="academic">Academic</option>
                                                            <option value="sports">Sports</option>
                                                            <option value="extracurricular">Extracurricular</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Achievement Title</label>
                                                        <input type="text" name="title" placeholder="e.g. 1st Place Science Fair, Football MVP" required>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Description / Details</label>
                                                        <textarea name="description" rows="3" placeholder="Enter specific details of the achievement..."></textarea>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Participation Status</label>
                                                        <select name="participation_status" required>
                                                            <option value="interested">Interested</option>
                                                            <option value="participated">Participated</option>
                                                            <option value="prize_winner">Prize Winner</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Achievement Date</label>
                                                        <input type="date" name="achievement_date" value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <button type="submit" class="btn-dash btn-dash-primary">
                                                        <i class="fas fa-award"></i> Log Student Achievement
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Custom Library Tab Pane -->
                            <div role="tabpanel" class="tab-pane fade" id="customLibrary">
                                <div class="custom-dashboard-wrapper">
                                    <div class="glass-card">
                                        <h3><i class="fas fa-book text-info"></i> Issued Books & Library Status</h3>
                                        @if($library_issues->count() > 0)
                                            <div class="table-wrap-dash">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Book Title</th>
                                                            <th>Author</th>
                                                            <th>ISBN Number</th>
                                                            <th>Given Date</th>
                                                            <th>Due Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($library_issues as $issue)
                                                            <tr>
                                                                <td><strong>{{ $issue->book_title }}</strong></td>
                                                                <td>{{ $issue->author_name }}</td>
                                                                <td>{{ $issue->isbn_no ?? 'N/A' }}</td>
                                                                <td>{{ dateConvert($issue->given_date) }}</td>
                                                                <td>{{ dateConvert($issue->due_date) }}</td>
                                                                <td>
                                                                    @if($issue->issue_status == 'R')
                                                                        <span class="badge-dash badge-good"><i class="fas fa-check-circle"></i> Returned</span>
                                                                    @else
                                                                        @php
                                                                            $overdue = strtotime($issue->due_date) < time();
                                                                        @endphp
                                                                        @if($overdue)
                                                                            <span class="badge-dash badge-misbehavior"><i class="fas fa-exclamation-triangle"></i> Overdue (Pending)</span>
                                                                        @else
                                                                            <span class="badge-dash badge-average"><i class="fas fa-clock"></i> Issued / Pending</span>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                                <p class="text-muted">No books currently checked out or tracked.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Custom Spending Tab Pane -->
                            <div role="tabpanel" class="tab-pane fade" id="customSpending">
                                <div class="custom-dashboard-wrapper">
                                    <div class="stats-grid-dash">
                                        <div class="stat-card-dash">
                                            <span class="stat-value">{{ $currency }}{{ number_format($total_spending, 2) }}</span>
                                            <span class="stat-label">Canteen & Other Spendings</span>
                                            <i class="fas fa-utensils stat-icon" style="color: var(--orange-dash)"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-receipt text-warning"></i> Canteen & Personal Transactions</h3>
                                                @if($spending->count() > 0)
                                                    <div class="table-wrap-dash">
                                                        <table>
                                                            <thead>
                                                                <tr>
                                                                    <th>Category</th>
                                                                    <th>Amount</th>
                                                                    <th>Description</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($spending as $spend)
                                                                    <tr>
                                                                        <td><span class="badge-dash badge-sports">{{ ucfirst($spend->category) }}</span></td>
                                                                        <td style="font-weight: 700; color: #ffffff;">{{ $currency }}{{ number_format($spend->amount, 2) }}</td>
                                                                        <td>{{ $spend->description }}</td>
                                                                        <td>{{ dateConvert($spend->spending_date) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                                        <p class="text-muted">No personal spending transactions registered yet.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-5">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-plus-circle text-primary"></i> Add Spending Transaction</h3>
                                                <form id="spendingForm" class="form-glass" onsubmit="submitCustomForm(event, 'spending', 'spendingForm')">
                                                    <div class="form-group-dash">
                                                        <label>Category</label>
                                                        <select name="category" required>
                                                            <option value="canteen">Canteen Billing</option>
                                                            <option value="bookstore">Bookstore</option>
                                                            <option value="cocurricular">Co-curricular Expenses</option>
                                                            <option value="misc">Miscellaneous</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Amount ({{ $currency }})</label>
                                                        <input type="number" step="0.01" name="amount" placeholder="0.00" required>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Description</label>
                                                        <textarea name="description" rows="3" placeholder="Enter transaction details (e.g. Purchased school blazer, Lunch buffet)..." required></textarea>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Spending Date</label>
                                                        <input type="date" name="spending_date" value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <button type="submit" class="btn-dash btn-dash-primary">
                                                        <i class="fas fa-wallet"></i> Save Billing Transaction
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Custom Communication Tab Pane -->
                            <div role="tabpanel" class="tab-pane fade" id="customComms">
                                <div class="custom-dashboard-wrapper">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-envelope-open-text text-primary"></i> Dispatch Logs & Multi-Channel Alerts</h3>
                                                @if($comms->count() > 0)
                                                    <div class="timeline-dash">
                                                        @foreach($comms as $cm)
                                                            <div class="timeline-item-dash average">
                                                                <div class="time-dash"><i class="far fa-clock"></i> {{ dateConvert($cm->sent_at ?? date('Y-m-d')) }}</div>
                                                                <div class="title-dash">
                                                                    <span class="badge-dash badge-academic">{{ strtoupper($cm->channel) }}</span>
                                                                    - {{ $cm->subject }}
                                                                </div>
                                                                <div class="desc-dash">
                                                                    <p style="margin-top: 4px; margin-bottom: 2px;">{{ $cm->message }}</p>
                                                                    <small class="text-muted">Type: <strong>{{ ucfirst($cm->event_type) }}</strong> | Sent By: {{ $cm->sent_by }}</small>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                                        <p class="text-muted">No communication alerts sent yet.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-5">
                                            <div class="glass-card">
                                                <h3><i class="fas fa-paper-plane text-primary"></i> Send Alert Notification</h3>
                                                <form id="commForm" class="form-glass" onsubmit="submitCustomForm(event, 'communications', 'commForm')">
                                                    <div class="form-group-dash">
                                                        <label>Alert Channel</label>
                                                        <select name="channel" required>
                                                            <option value="email">Email Notification</option>
                                                            <option value="sms">SMS Alert Message</option>
                                                            <option value="group">Group Broadcast Alert</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Event / Notification Type</label>
                                                        <select name="event_type" required>
                                                            <option value="general">General Broadcast</option>
                                                            <option value="sports">Sports Activities Alert</option>
                                                            <option value="training">Training Sessions Alert</option>
                                                            <option value="school_event">School Special Events</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Alert Subject</label>
                                                        <input type="text" name="subject" placeholder="Enter alert subject..." required>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Alert Message Body</label>
                                                        <textarea name="message" rows="4" placeholder="Type notification content..." required></textarea>
                                                    </div>
                                                    <div class="form-group-dash">
                                                        <label>Authorized Sender</label>
                                                        <input type="text" name="sent_by" value="{{ Auth::user()->full_name ?? 'School Admin' }}" required>
                                                    </div>
                                                    <button type="submit" class="btn-dash btn-dash-primary">
                                                        <i class="fas fa-paper-plane"></i> Send Alert Notification
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Generic interactive forms script -->
                            <script>
                                async function submitCustomForm(event, endpoint, formId) {
                                    event.preventDefault();
                                    const form = document.getElementById(formId);
                                    const submitBtn = form.querySelector('button[type="submit"]');
                                    const origText = submitBtn.innerHTML;
                                    submitBtn.disabled = true;
                                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

                                    const formData = new FormData(form);
                                    const data = {};
                                    formData.forEach((value, key) => data[key] = value);
                                    
                                    // Add student ID to payloads
                                    data['student_id'] = "{{ $student_detail->id }}";

                                    try {
                                        const response = await fetch("{{ url('/') }}/api.php/" + endpoint, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(data)
                                        });
                                        const result = await response.json();
                                        if (result.success) {
                                            alert('Record saved successfully!');
                                            window.location.reload();
                                        } else {
                                            alert('Error saving record: ' + (result.error || 'Unknown error'));
                                            submitBtn.disabled = false;
                                            submitBtn.innerHTML = origText;
                                        }
                                    } catch (error) {
                                        console.error('Submission failed:', error);
                                        alert('Failed to submit form: ' + error.message);
                                        submitBtn.disabled = false;
                                        submitBtn.innerHTML = origText;
                                    }
                                }
                            </script>
                        </div>
                    </div>

                   
                </div>
            </div>
            <!-- End Student Details -->
        </div>
        </div>
    </section>

    <!-- assign class form modal start-->
    <div class="modal fade admin-query" id="assignClass">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        @if (moduleStatusCheck('University'))
                            @lang('university::un.assign_faculty_department')
                        @else
                            @lang('student.assign_class')
                        @endif
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        {{ html()->form('POST', route('student.record.store'))->attributes([
                            'class' => 'form-horizontal',
                            'files' => true,
                            'enctype' => 'multipart/form-data',
                        ])->open() }}

                        <input type="hidden" name="student_id" value="{{ $student_detail->id }}">
                        @if (!moduleStatusCheck('University'))
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="primary_input ">
                                        <select
                                            class="primary_select  form-control{{ $errors->has('session') ? ' is-invalid' : '' }}"
                                            name="session" id="academic_year">
                                            <option data-display="@lang('common.academic_year') *" value="">@lang('common.academic_year')
                                                *</option>
                                            @foreach ($sessions as $session)
                                                <option value="{{ $session->id }}"
                                                    {{ old('session') == $session->id ? 'selected' : '' }}>
                                                    {{ $session->year }}[{{ $session->title }}]</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('session'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('session') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="primary_input " id="class-div">
                                        <select
                                            class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                            name="class" id="classSelectStudent">
                                            <option data-display="@lang('common.class') *" value="">@lang('common.class')
                                                *</option>
                                        </select>
                                        <div class="pull-right loader loader_style" id="select_class_loader">
                                            <img class="loader_img_style"
                                                src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
                                        </div>

                                        @if ($errors->has('class'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('class') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="primary_input " id="sectionStudentDiv">
                                        <select
                                            class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                            name="section" id="sectionSelectStudent">
                                            <option data-display="@lang('common.section') *" value="">@lang('common.section')
                                                *</option>
                                        </select>
                                        <div class="pull-right loader loader_style" id="select_section_loader">
                                            <img class="loader_img_style"
                                                src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
                                        </div>

                                        @if ($errors->has('section'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('section') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            @includeIf('university::common.session_faculty_depart_academic_semester_level', [
                                'mt' => 'mt-0',
                                'required' => ['USN', 'UF', 'UD', 'UA', 'US', 'USL'],
                                'row' => 1,
                                'div' => 'col-lg-12',
                                'hide' => ['USUB'],
                            ])
                        @endif
                        @if (generalSetting()->multiple_roll == 1)
                            <div class="row mt-25">
                                <div class="col-lg-12">
                                    <div class="primary_input ">
                                        <input oninput="numberCheck(this)" class="primary_input_field" type="text"
                                            placeholder="{{ moduleStatusCheck('Lead') == true ? __('lead::lead.id_number') : __('student.roll') }}{{ is_required('roll_number') == true ? ' *' : '' }}"
                                            id="roll_number" name="roll_number" value="{{ old('roll_number') }}">
                                        <span class="text-danger" id="roll-error" role="alert">
                                            <strong></strong>
                                        </span>
                                        @if ($errors->has('roll_number'))
                                            <span class="text-danger">
                                                {{ $errors->first('roll_number') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row  mt-25">
                            <div class="col-lg-12">
                                <label for="is_default">@lang('student.is_default')</label>
                                <div class="d-flex radio-btn-flex mt-10">

                                    <div class="mr-30">
                                        <input type="radio" name="is_default" id="isDefaultYes" value="1"
                                            class="common-radio relationButton">
                                        <label for="isDefaultYes">@lang('common.yes')</label>
                                    </div>
                                    <div class="mr-30">
                                        <input type="radio" name="is_default" id="isDefaultNo" value="0"
                                            class="common-radio relationButton" checked>
                                        <label for="isDefaultNo">@lang('common.no')</label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 text-center mt-20">
                            <div class="mt-40 d-flex justify-content-between">
                                <button type="button" class="primary-btn tr-bg"
                                    data-dismiss="modal">@lang('admin.cancel')</button>
                                <button class="primary-btn fix-gr-bg submit" id="save_button_query"
                                    type="submit">@lang('admin.save')</button>
                            </div>
                        </div>
                        {{ html()->form()->close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- assign class form modal end-->

    <!-- timeline form modal start-->
    <div class="modal fade admin-query" id="add_timeline_madal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('student.add_timeline')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        {{ html()->form('POST', route('student_timeline_store'))->attributes([
                            'class' => 'form-horizontal',
                            'files' => true,
                            'enctype' => 'multipart/form-data',
                            'name' => 'document_upload',
                        ])->open() }}
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="student_id" value="{{ $student_detail->id }}">
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <label>@lang('student.title') <span>*</span> </label>
                                            <input class="primary_input_field form-control{" type="text"
                                                name="title" value="" id="title" maxlength="200">
                                            <span class="focus-border"></span>
                                            <span class=" text-danger" role="alert" id="amount_error">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="input-right-icon">
                                    <div class="input-effect">
                                        <label>@lang('common.date')</label>
                                        <div class="position-relative">
                                            <input class="primary_input_field date form-control" readonly id="startDate"
                                                type="text" name="date">
                                            <span class="focus-border"></span>
                                            <label class="primary_input-icon mr-2" for="startDate">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="input-effect">
                                    <label>@lang('common.description')<span></span> </label>
                                    <textarea class="primary_input_field form-control" cols="0" rows="3" name="description"
                                        id="Description"></textarea>
                                    <span class="focus-border textarea"></span>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-40">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="input-effect">
                                            <input class="primary_input_field" type="text"
                                                id="placeholderFileFourName" placeholder="Document" disabled>
                                            <span class="focus-border"></span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="document_file_4">@lang('common.browse')</label>
                                            <input type="file" class="d-none" name="document_file_4"
                                                id="document_file_4">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <input type="checkbox" id="currentAddressCheck" class="common-checkbox"
                                    name="visible_to_student" value="1">
                                <label for="currentAddressCheck">@lang('student.visible_to_this_person')</label>
                            </div>

                            <div class="col-lg-12 text-center mt-40">
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                        data-dismiss="modal">@lang('common.cancel')</button>
                                    <button class="primary-btn fix-gr-bg submit"
                                        type="submit">@lang('common.save')</button>
                                </div>
                            </div>
                        </div>
                        {{ html()->form()->close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- timeline form modal end-->


    @include('backEnd.partials.data_table_js')
    @include('backEnd.partials.date_picker_css_js')
    <script>
        function deleteDoc(id, doc) {
            var modal = $('#delete-doc');
            modal.find('input[name=student_id]').val(id)
            modal.find('input[name=doc_id]').val(doc)
            modal.modal('show');
        }
    </script>

@endsection
