@extends('backEnd.master')
@section('title')
    @lang('student.my_profile')
@endsection
@section('mainContent')
    @push('css')
        <style>
            .student-details .nav-tabs {
                margin-left: 10px;
            }

            /* #studentOnlineExam table.dataTable thead .sorting:after,
            #studentOnlineExam table.dataTable thead .sorting_asc:after,
            #leaves table.dataTable thead .sorting:after,
            #leaves table.dataTable thead .sorting_asc:after {
                top: 10px !important;
                left: 4px !important;
            }

            #studentOnlineExam table.dataTable thead .sorting_desc:after,
            #leaves table.dataTable thead .sorting_desc:after {
                top: 10px !important;
                left: 4px !important;
            } */

            .input-right-icon button.primary-btn-small-input {
                top: 8px !important;
                right: 12px !important;
            }

            div#table_id_wrapper {
                margin-top: 50px;
            }

            table.dataTable thead th {
                padding-left: 18px !important;
            }


            @media (max-width: 767px) {
                .dataTables_filter label {
                    top: -25px !important;
                    width: 50%;
                    left: 30% !important;
                }
            }

            @media screen and (max-width: 640px) {

                .dataTables_filter label {
                    left: 50% !important;
                }

                div.dt-buttons {
                    display: none;
                }

                .dataTables_filter label {
                    top: -60px !important;
                    width: 100%;
                    float: right;
                }

                .main-title {
                    margin-bottom: 40px
                }
            }

            .check_box_table .QA_table .table tbody td:first-child {
                padding-left: 35px !important;
                padding-right: 24px !important;
            }

            /* #table_id.table.dataTable thead .sorting_asc:first-child::after,
            #table_id.table.dataTable thead .sorting_desc:first-child::after,
            #table_id.table.dataTable thead .sorting:first-child::after {
                left: 4px !important;
                top: 10px !important;
            } */
            .simple-table thead tr th{
                white-space: nowrap;
            }

            div#studentOnlineExam table.dataTable .sorting_asc:after{
                left: 4px!important;
                top: 10px;
            }

            div#studentOnlineExam table.dataTable .sorting:after{
                left: 4px!important;
                top: 10px;
            }

            div#studentOnlineExam table.dataTable .sorting_desc:after{
                left: 4px!important;
                top: 10px;
            }
            .linkopenmodal{
                font-size: 12px!important;
                padding: 2px 6px!important;
            }
            table thead tr th{
                white-space: nowrap;
            }
            table tbody tr td:first-child{
                padding-left: 22px!important;
            }

            .school-table-style-parent-fees tbody tr td:first-child{
                padding-left: 0!important;
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
        @if (moduleStatusCheck('University'))
            <style>
                .school-table-up-style tr td {
                    padding: 8px 6px 8px 0px !important;
                    font-size: 14px !important;
                }

                .school-table-style {
                    padding: 0px !important;
                }
            </style>
        @endif
    @endpush
    @php
        $setting = app('school_info');
        if (!empty($setting->currency_symbol)) {
            $currency = $setting->currency_symbol;
        } else {
            $currency = '$';
        }
        
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

    <section class="student-details">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3 mb-30">
                    <!-- Start Student Meta Information -->
                    @if (moduleStatusCheck('University'))
                        @includeIf('university::promote.inc.student_profile', [
                            'student_detail' => $student_detail->defaultClass,
                            'setting' => null,
                            'student' => $student_detail,
                        ])
                    @else
                        @includeIf('backEnd.studentInformation.inc.student_profile')
                    @endif
                    <!-- End Siblings Meta Information -->
                </div>

                <!-- Start Student Details -->
                <div class="col-lg-9">
                    <div class="white-box">
                        <ul class="nav nav-tabs tabs_scroll_nav mb-10" role="tablist">
                            @if (userPermission('student-profile.profile'))
                                <li class="nav-item">
                                    <a class="nav-link @if (Session::get('studentDocuments') != 'active' && Session::get('studentTimeline') != 'active') active @endif" href="#studentProfile"
                                        role="tab" data-toggle="tab"> @lang('student.profile') </a>
                                </li>
                            @endif
                            @if (generalSetting()->fees_status == 0)
                                @if (userPermission('student-profile.fees'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="#studentFees" role="tab"
                                            data-toggle="tab">@lang('fees.fees')</a>
                                    </li>
                                @endif
                            @endif
                            @if (userPermission('student-apply-leave'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#leaves" role="tab" data-toggle="tab">@lang('leave.leave')</a>
                                </li>
                            @endif
                            @if (userPermission('student-profile.exam'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#studentExam" role="tab"
                                        data-toggle="tab">@lang('exam.exam')</a>
                                </li>
                            @endif
    
                            @if (moduleStatusCheck('University'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#studentPanelExamTranscript" role="tab"
                                        data-toggle="tab">@lang('university::un.transcript')</a>
                                </li>
                            @endif
    
                            <li class="nav-item">
                                <a class="nav-link" href="#studentOnlineExam" role="tab"
                                    data-toggle="tab">@lang('exam.online_exam')</a>
                            </li>
                            @if (userPermission('student-profile.document'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Session::get('studentDocuments') == 'active' ? 'active' : '' }}"
                                        href="#studentDocuments" role="tab" data-toggle="tab">@lang('student.documents')</a>
                                </li>
                            @endif
                            @if (userPermission('studentTimeline'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Session::get('studentTimeline') == 'active' ? 'active' : '' }} "
                                        href="#studentTimeline" role="tab" data-toggle="tab">@lang('student.record')</a>
                                </li>
                            @endif
    
                            @if (userPermission('studentTimeline') && moduleStatusCheck('University'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Session::get('chooseSubject') == 'active' ? 'active' : '' }} "
                                        href="#chooseSubject" role="tab" data-toggle="tab">@lang('student.subject')</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link {{ Session::get('studentAttendance') == 'active' ? 'active' : '' }} "
                                    href="#studentAttendance" role="tab" data-toggle="tab">@lang('student.my_attendance')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Session::get('subjectAttendance') == 'active' ? 'active' : '' }} "
                                    href="#subjectAttendance" role="tab" data-toggle="tab">@lang('student.subject_attendance')</a>
                            </li>
                            @if (moduleStatusCheck('Wallet'))
                                @if (userPermission('wallet.my-wallet'))
                                    <li class="nav-item">
                                        <a class="nav-link {{ Session::get('studentWallet') == 'active' ? 'active' : '' }} "
                                            href="#studentWallet" role="tab" data-toggle="tab"> @lang('wallet::wallet.wallet')</a>
                                    </li>
                                @endif
                            @endif
                            @if (moduleStatusCheck('BehaviourRecords'))
                                @if ($behaviourRecordSetting->student_view == 1)
                                    <li class="nav-item">
                                        <a class="nav-link {{ Session::get('studentBehaviourRecord') == 'active' ? 'active' : '' }} "
                                            href="#studentBehaviourRecord" role="tab"
                                            data-toggle="tab">@lang('student.behaviour_record')</a>
                                    </li>
                                @endif
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
                            
                            @if (userPermission('update-my-profile'))
                                <li class="nav-item edit-button">
                                    <a href="{{ route('update-my-profile', $student_detail->id) }}"
                                        class="primary-btn small fix-gr-bg pull-right">@lang('common.edit')</a>
                                </li>
                            @endif
                        </ul>
    
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Start Profile Tab -->
                            <div role="tabpanel" class="tab-pane fade @if (Session::get('studentDocuments') != 'active' && Session::get('studentTimeline') != 'active') show active @endif"
                                id="studentProfile">
                                <div>
                                    <h4 class="stu-sub-head">@lang('student.personal_info')</h4>
                                    @if (is_show('admission_date'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.admission_date')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ @$student_detail->admission_date != '' ? dateConvert(@$student_detail->admission_date) : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
    
                                    @if (is_show('date_of_birth'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('common.date_of_birth')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->date_of_birth != '' ? dateConvert(@$student_detail->date_of_birth) : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
    
                                    @if (is_show('student_category_id'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('common.type')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->category != '' ? @$student_detail->category->category_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('religion'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('student.religion')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->religion != '' ? @$student_detail->religion->base_setup_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
    
                                    @if (is_show('phone_number'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('common.phone_number')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->mobile }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('email_address'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('common.email_address')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- changes for lead module --abunayem --}}
                                    @if (moduleStatusCheck('Lead') == true)
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('lead::lead.city')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->leadCity->city_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('lead::lead.source')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->source->source_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- end --}}
                                    @if (is_show('current_address'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('student.present_address')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->current_address }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('permanent_address'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6">
                                                    <div class="">
                                                        @lang('student.permanent_address')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-7">
                                                    <div class="">
                                                        {{ @$student_detail->permanent_address }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
    
                                    <!-- Start Parent Part -->
                                    <h4 class="stu-sub-head mt-40">@lang('student.parent_/_guardian_details')</h4>
                                    <div class="d-flex">
                                        @if (is_show('fathers_photo'))
                                            <div class="mt-20">
                                                <img class="student-meta-img img-100"
                                                    src="{{ @$student_detail->parents != '' ? asset(@$student_detail->parents->fathers_photo) : '' }}"
                                                    alt="">
                                            </div>
                                        @endif
                                        <div class="w-100">
                                            @if (is_show('fathers_name'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('student.father_name')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->fathers_name : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (is_show('fathers_occupation'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('student.occupation')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->fathers_occupation : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
    
                                            @if (is_show('fathers_phone'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('common.phone_number')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->fathers_mobile : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="d-flex">
                                        @if (is_show('mothers_photo'))
                                            <div class="mt-20">
                                                <img class="student-meta-img img-100"
                                                    src="{{ @$student_detail->parents != '' ? asset(@$student_detail->parents->mothers_photo) : '' }}"
                                                    alt="">
                                            </div>
                                        @endif
                                        <div class="w-100">
                                            <div class="single-info">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="">
                                                            @lang('student.mother_name')
                                                        </div>
                                                    </div>
                                                    @if (is_show('mothers_name'))
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->mothers_name : '' }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
    
                                            <div class="single-info">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="">
                                                            @lang('student.occupation')
                                                        </div>
                                                    </div>
                                                    @if (is_show('mothers_occupation'))
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->mothers_occupation : '' }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (is_show('mothers_phone'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('common.phone_number')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->mothers_mobile : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="d-flex">
                                        @if (is_show('guardians_photo'))
                                            <div class="mt-20">
                                                <img class="student-meta-img img-100"
                                                    src="{{ @$student_detail->parents != '' ? asset(@$student_detail->parents->guardians_photo) : '' }}"
                                                    alt="">
                                            </div>
                                        @endif
                                        <div class="w-100">
                                            @if (is_show('guardians_name'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('student.guardian_name')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->guardians_name : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
    
                                            @if (is_show('guardians_email'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('common.email_address')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->guardians_email : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (is_show('guardians_phone'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('common.phone_number')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->guardians_mobile : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
    
                                            <div class="single-info">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="">
                                                            @lang('student.relation_with_guardian')
                                                        </div>
                                                    </div>
    
                                                    <div class="col-lg-8 col-md-7">
                                                        <div class="">
                                                            {{ @$student_detail->parents != '' ? @$student_detail->parents->guardians_relation : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (is_show('guardians_occupation'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('student.occupation')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->guardians_occupation : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (is_show('guardians_address'))
                                                <div class="single-info">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="">
                                                                @lang('student.guardian_address')
                                                            </div>
                                                        </div>
    
                                                        <div class="col-lg-8 col-md-7">
                                                            <div class="">
                                                                {{ @$student_detail->parents != '' ? @$student_detail->parents->guardians_address : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- End Parent Part -->
    
                                    <!-- Start Transport Part -->
                                    <h4 class="stu-sub-head mt-40">@lang('student.transport_and_dormitory_details')</h4>
                                    @if (is_show('route'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('common.route')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ @$student_detail->route != '' ? @$student_detail->route->title : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('vehicle'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('transport.vehicle_number')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ @$student_detail->vehicle != '' ? @$student_detail->vehicle->vehicle_no : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
    
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5">
                                                <div class="">
                                                    @lang('transport.driver_name')
                                                </div>
                                            </div>
    
                                            <div class="col-lg-7 col-md-6">
                                                <div class="">
                                                    {{ @$student_detail->vehicle != '' ? @$driver->full_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5">
                                                <div class="">
                                                    @lang('transport.driver_phone_number')
                                                </div>
                                            </div>
    
                                            <div class="col-lg-7 col-md-6">
                                                <div class="">
                                                    {{ @$student_detail->vehicle != '' ? @$driver->mobile : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (is_show('dormitory_name'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('dormitory.dormitory_name')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ @$student_detail->dormitory != '' ? @$student_detail->dormitory->dormitory_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- End Transport Part -->
    
                                    <!-- Start Other Information Part -->
                                    <h4 class="stu-sub-head mt-40">@lang('student.information_other')</h4>
                                    @if (is_show('blood_group'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.blood_group')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ @$student_detail->bloodGroup != '' ? @$student_detail->bloodGroup->base_setup_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('height'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.height')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->height) ? @$student_detail->height : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('weight'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.weight')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->weight) ? @$student_detail->weight : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('previous_school_details'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.previous_school_details')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->previous_school_details) ? @$student_detail->previous_school_details : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('national_id_number'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.national_identification_number')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->national_id_no) ? @$student_detail->national_id_no : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('local_id_number'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.local_identification_number')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->local_id_no) ? @$student_detail->local_id_no : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('bank_account_number'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('accounts.bank_account_number')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->bank_account_no) ? @$student_detail->bank_account_no : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('bank_name'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('accounts.bank_name')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->bank_name) ? @$student_detail->bank_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('ifsc_code'))
                                        <div class="single-info">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5">
                                                    <div class="">
                                                        @lang('student.ifsc_code')
                                                    </div>
                                                </div>
    
                                                <div class="col-lg-7 col-md-6">
                                                    <div class="">
                                                        {{ isset($student_detail->ifsc_code) ? @$student_detail->ifsc_code : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (is_show('custom_field'))
                                        {{-- Custom field start --}}
                                        @include('backEnd.customField._coutom_field_show')
                                        {{-- Custom field end --}}
                                    @endif
                                    <!-- End Other Information Part -->
                                </div>
                            </div>
                            <!-- End Profile Tab -->
    
                            <!-- Start Fees Tab -->
                            <div role="tabpanel" class="tab-pane fade" id="studentFees">
                                <div class="table-responsive">
                                    @foreach ($records as $record)
                                        <div class="no-search no-paginate no-table-info mb-2">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="main-title">
                                                        @if (moduleStatusCheck('University'))
                                                            <h3 class="mb-10">{{ @$record->name }}
                                                                ({{ @$record->unDepartment->name }} -
                                                                {{ @$record->unSection->section_name }})
                                                            </h3>
                                                        @else
                                                            <h3 class="mb-10">{{ $record->class->class_name }}
                                                                ({{ $record->section->section_name }})</h3>
                                                        @endif
                                                    </div>
                                                </div>
    
                                                @if (moduleStatusCheck('University'))
                                                    <div class="col-lg-8 mb-10">
                                                        <table class="table school-table-style res_scrol school-table-up-style"
                                                            cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('fees.fees_type')</th>
                                                                    <th>@lang('fees.assigned_date')</th>
                                                                    <th>@lang('fees.amount')</th>
                                                                </tr>
                                                            </thead>
                                                            @php $gt_fees = 0; @endphp
                                                            <tbody>
                                                                @foreach ($record->fees as $assign_fees)
                                                                    @php $gt_fees += $assign_fees->fees_amount; @endphp
                                                                    <tr>
                                                                        <td>{{ @$assign_fees->feesGroupMaster->feesTypes->name }}
                                                                        </td>
                                                                        <td>{{ dateConvert($assign_fees->created_at) }}</td>
                                                                        <td> {{ currency_format($assign_fees->fees_amount) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            <tfoot>
                                                                <tr>
                                                                    <th>@lang('fees.grand_total')
                                                                        ({{ generalSetting()->currency_symbol }})</th>
                                                                    <th></th>
                                                                    <th> {{ currency_format($gt_fees) }}</th>
                                                                </tr>
                                                            </tfoot>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                            @if (moduleStatusCheck('University'))
                                                @includeIf('university::include.studentPanelFeesPay')
                                            @elseif(directFees())
                                                @includeIf('backEnd.feesCollection.directFees.studentDirectFeesPay')
                                            @else
                                                <table class="table school-table-style" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('fees.fees_group')</th>
                                                            <th>@lang('fees.fees_code')</th>
                                                            <th>@lang('fees.due_date')</th>
                                                            <th>@lang('common.status')</th>
                                                            <th>@lang('fees.amount') ({{ generalSetting()->currency_symbol }})
                                                            </th>
                                                            <th>@lang('fees.payment_id')</th>
                                                            <th>@lang('fees.mode')</th>
                                                            <th>@lang('common.date')</th>
                                                            <th>@lang('fees.discount') ({{ generalSetting()->currency_symbol }})
                                                            </th>
                                                            <th>@lang('fees.fine')({{ generalSetting()->currency_symbol }})
                                                            </th>
                                                            <th>@lang('fees.paid') ({{ generalSetting()->currency_symbol }})
                                                            </th>
                                                            <th>@lang('fees.balance')</th>
                                                        </tr>
                                                    </thead>
    
                                                    <tbody>
                                                        @php
                                                            @$grand_total = 0;
                                                            @$total_fine = 0;
                                                            @$total_discount = 0;
                                                            @$total_paid = 0;
                                                            @$total_grand_paid = 0;
                                                            @$total_balance = 0;
                                                        @endphp
                                                        @foreach ($fees_assigneds as $fees_assigned)
                                                            @if ($fees_assigned->record_id == $record->id)
                                                                @php
                                                                    @$grand_total += @$fees_assigned->feesGroupMaster->amount;
    
                                                                @endphp
    
                                                                @php
                                                                    @$discount_amount = $fees_assigned->applied_discount;
                                                                    @$total_discount += @$discount_amount;
                                                                    @$student_id = @$fees_assigned->student_id;
                                                                @endphp
                                                                @php
                                                                    @$paid = App\SmFeesAssign::discountSum(@$fees_assigned->student_id, @$fees_assigned->feesGroupMaster->feesTypes->id, 'amount', $fees_assigned->record_id);
                                                                    @$total_grand_paid += @$paid;
                                                                @endphp
                                                                @php
                                                                    @$fine = App\SmFeesAssign::discountSum(@$fees_assigned->student_id, @$fees_assigned->feesGroupMaster->feesTypes->id, 'fine', $fees_assigned->record_id);
                                                                    @$total_fine += @$fine;
                                                                @endphp
    
                                                                @php
                                                                    @$total_paid = @$discount_amount + @$paid;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ @$fees_assigned->feesGroupMaster->feesGroups != '' ? @$fees_assigned->feesGroupMaster->feesGroups->name : '' }}
                                                                    </td>
                                                                    <td>{{ @$fees_assigned->feesGroupMaster->feesTypes != '' ? @$fees_assigned->feesGroupMaster->feesTypes->name : '' }}
                                                                    </td>
                                                                    <td>
                                                                        @if (!empty(@$fees_assigned->feesGroupMaster))
                                                                            {{ @$fees_assigned->feesGroupMaster->date != '' ? dateConvert(@$fees_assigned->feesGroupMaster->date) : '' }}
                                                                        @endif
                                                                    </td>
                                                                    @php
                                                                        $total_payable_amount = $fees_assigned->fees_amount;
                                                                        $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                                                        $balance_amount = number_format($rest_amount + $fine, 2, '.', '');
                                                                        $total_balance += $balance_amount;
                                                                    @endphp
                                                                    <td>
    
                                                                        @if ($balance_amount == 0)
                                                                            <button
                                                                                class="primary-btn small bg-success text-white border-0 text-nowrap">@lang('fees.paid')</button>
                                                                        @elseif($paid != 0)
                                                                            <button
                                                                                class="primary-btn small bg-warning text-white border-0 text-nowrap">@lang('fees.partial')</button>
                                                                        @elseif($paid == 0)
                                                                            <button
                                                                                class="primary-btn small bg-danger text-white border-0 text-nowrap">@lang('fees.unpaid')</button>
                                                                        @endif
    
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            echo number_format($fees_assigned->feesGroupMaster->amount, 2, '.', '');
                                                                        @endphp
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td> {{ @$discount_amount }} </td>
                                                                    <td>{{ @$fine }}</td>
                                                                    <td>{{ @$paid }}</td>
                                                                    <td>
                                                                        @php
                                                                            echo @$balance_amount;
                                                                        @endphp
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    @$payments = App\SmFeesAssign::feesPayment(@$fees_assigned->feesGroupMaster->feesTypes->id, @$fees_assigned->student_id, $fees_assigned->recordDetail->id);
                                                                    $i = 0;
                                                                @endphp
    
                                                                @foreach ($payments as $payment)
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td class="text-right"><img
                                                                                src="{{ asset('public/backEnd/img/table-arrow.png') }}">
                                                                        </td>
                                                                        <td>
                                                                            @php
                                                                                @$created_by = App\User::find(@$payment->created_by);
                                                                            @endphp
                                                                            @if (@$created_by != '')
                                                                                <a href="#" data-toggle="tooltip"
                                                                                    data-placement="right"
                                                                                    title="{{ 'Collected By: ' . @$created_by->full_name }}">{{ @$payment->fees_type_id . '/' . @$payment->id }}</a>
                                                                        </td>
                                                                @endif
                                                                <td>
                                                                    {{ $payment->payment_mode }}
                                                                </td>
                                                                <td class="nowrap">
                                                                    {{ @$payment->payment_date != '' ? dateConvert(@$payment->payment_date) : '' }}
                                                                </td>
                                                                <td>
                                                                    {{ @$payment->discount_amount }}
                                                                </td>
                                                                <td>
                                                                    {{ @$payment->fine }}
                                                                    @if ($payment->fine != 0)
                                                                        @if (strlen($payment->fine_title) > 14)
                                                                            <span class="text-danger nowrap"
                                                                                title="{{ $payment->fine_title }}">
                                                                                ({{ substr($payment->fine_title, 0, 15) . '...' }})
                                                                            </span>
                                                                        @else
                                                                            @if ($payment->fine_title == '')
                                                                                {{ $payment->fine_title }}
                                                                            @else
                                                                                <span class="text-danger nowrap">
                                                                                    ({{ $payment->fine_title }})
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ @$payment->amount }}
                                                                </td>
                                                                <td></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                            @endforeach
    
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>@lang('fees.grand_total') ({{ @generalSetting()->currency_symbol }})</th>
                                                    <th></th>
                                                    <th>{{ @$grand_total }}</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>{{ @$total_discount }}</th>
                                                    <th>{{ @$total_fine }}</th>
                                                    <th>{{ @$total_grand_paid }}</th>
                                                    <th>{{ number_format($total_balance, 2, '.', '') }} </th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                            </table>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- End Profile Tab -->
                        <!-- Start leave Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="leaves">
                            <div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <x-table>
                                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('leave.leave_type')</th>
                                                        <th>@lang('leave.leave_from') </th>
                                                        <th>@lang('leave.leave_to')</th>
                                                        <th>@lang('leave.apply_date')</th>
                                                        <th>@lang('common.status')</th>
                                                        <th>@lang('common.action')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @php $diff = ''; @endphp
                                                        @if (count($leave_details) > 0) --}}
                                                    @foreach ($leave_details as $value)
                                                        <tr>
                                                            <td>{{ @$value->leaveType->type }}</td>
                                                            <td>{{ $value->leave_from != '' ? dateConvert($value->leave_from) : '' }}
                                                            </td>
                                                            <td>{{ $value->leave_to != '' ? dateConvert($value->leave_to) : '' }}
                                                            </td>
                                                            <td>{{ $value->apply_date != '' ? dateConvert($value->apply_date) : '' }}
                                                            </td>
                                                            <td>
                                                                @if ($value->approve_status == 'P')
                                                                    <button
                                                                        class="primary-btn small bg-warning text-white border-0 text-nowrap">
                                                                        @lang('common.pending')</button>
                                                                @endif
    
                                                                @if ($value->approve_status == 'A')
                                                                    <button
                                                                        class="primary-btn small bg-success text-white border-0 text-nowrap">
                                                                        @lang('common.approved')</button>
                                                                @endif
    
                                                                @if ($value->approve_status == 'C')
                                                                    <button
                                                                        class="primary-btn small bg-danger text-white border-0 text-nowrap">
                                                                        @lang('common.cancelled')</button>
                                                                @endif
    
                                                            </td>
                                                            <td>
                                                                <a class="modalLink" data-modal-size="modal-md"
                                                                    title="@lang('common.view_leave_details')"
                                                                    href="{{ url('view-leave-details-apply', $value->id) }}"><button
                                                                        class="primary-btn small tr-bg"> @lang('common.view')
                                                                    </button></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    {{-- @else
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>@lang('leave.not_leaves_data')</td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        @endif  --}}
                                                </tbody>
                                            </table>
                                        </x-table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End leave Tab -->
    
                        <!-- Start Exam Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="studentExam">
                            @if (moduleStatusCheck('University'))
                                {{-- @includeIf('university::exam.partials._exam_report') --}}
                                @includeIf('university::exam.student_exam_tab')
                            @else
                                @foreach ($student_detail->studentRecords as $record)
                                    @php
                                        $today = date('Y-m-d H:i:s');
                                        $exam_count = count($exam_terms);
                                    @endphp
                                    @if ($exam_count < 1)
                                        <div class="no-search no-paginate no-table-info mb-2">
                                            <x-table>
                                            <table class="table" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('common.subject')</th>
                                                        <th>@lang('exam.full_marks')</th>
                                                        <th>@lang('exam.passing_marks')</th>
                                                        <th>@lang('exam.obtained_marks')</th>
                                                        <th>@lang('exam.results')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            </x-table>
                                        </div>
                                    @endif
                                    <div class="no-search no-paginate no-table-info mb-2">
                                        @foreach ($exam_terms as $exam)
                                            @php
                                                $get_results = App\SmStudent::getExamResult(@$exam->id, @$record);
                                            @endphp
                                            @if ($get_results)
                                                <div class="main-title">
                                                    <h3 class="mb-0">{{ @$exam->title }}</h3>
                                                </div>
                                                @php
                                                    $grand_total = 0;
                                                    $grand_total_marks = 0;
                                                    $result = 0;
                                                    $temp_grade = [];
                                                    $total_gpa_point = 0;
                                                    $total_subject = count($get_results);
                                                    $optional_subject = 0;
                                                    $optional_gpa = 0;
                                                @endphp
                                                @isset($exam->examSettings->publish_date)
                                                    @if ($exam->examSettings->publish_date <= $today)
                                                        <x-table>
                                                        <table id="table_id" class="table" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('common.date')</th>
                                                                    <th>@lang('exam.subject_full_marks')</th>
                                                                    <th>@lang('exam.obtained_marks')</th>
                                                                    @if (@generalSetting()->result_type == 'mark')
                                                                        <th>@lang('exam.pass_fail')</th>
                                                                    @else
                                                                        <th>@lang('exam.grade')</th>
                                                                        <th>@lang('exam.gpa')</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($get_results as $mark)
                                                                    @php
                                                                        if (!is_null($optional_subject_setup) && !is_null($student_optional_subject)) {
                                                                            if ($mark->subject_id != @$student_optional_subject->subject_id) {
                                                                                $temp_grade[] = $mark->total_gpa_grade;
                                                                            }
                                                                        } else {
                                                                            $temp_grade[] = $mark->total_gpa_grade;
                                                                        }
                                                                        $total_gpa_point += $mark->total_gpa_point;
                                                                        if (!is_null(@$student_optional_subject)) {
                                                                            if (@$student_optional_subject->subject_id == $mark->subject->id && $mark->total_gpa_point < @$optional_subject_setup->gpa_above) {
                                                                                $total_gpa_point = $total_gpa_point - $mark->total_gpa_point;
                                                                            }
                                                                        }
                                                                        $temp_gpa[] = $mark->total_gpa_point;
                                                                        $get_subject_marks = subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id);
    
                                                                        $subject_marks = App\SmStudent::fullMarksBySubject($exam->id, $mark->subject_id);
                                                                        $schedule_by_subject = App\SmStudent::scheduleBySubject($exam->id, $mark->subject_id, @$record);
                                                                        $result_subject = 0;
                                                                        if (@generalSetting()->result_type == 'mark') {
                                                                            $grand_total_marks += subject100PercentMark();
                                                                        } else {
                                                                            $grand_total_marks += $get_subject_marks;
                                                                        }
                                                                        if (@$mark->is_absent == 0) {
                                                                            if (@generalSetting()->result_type == 'mark') {
                                                                                $grand_total += @subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id));
                                                                            } else {
                                                                                $grand_total += @$mark->total_marks;
                                                                            }
                                                                            if ($mark->marks < $subject_marks->pass_mark) {
                                                                                $result_subject++;
                                                                                $result++;
                                                                            }
                                                                        } else {
                                                                            $result_subject++;
                                                                            $result++;
                                                                        }
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            {{ !empty($schedule_by_subject->date) ? dateConvert($schedule_by_subject->date) : '' }}
                                                                        </td>
                                                                        <td>
                                                                            {{ @$mark->subject->subject_name }}
                                                                            @if (@generalSetting()->result_type == 'mark')
                                                                                ({{ subject100PercentMark() }})
                                                                            @else
                                                                                ({{ @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id) }})
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if (@generalSetting()->result_type == 'mark')
                                                                                {{ @subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id)) }}
                                                                            @else
                                                                                {{ @$mark->total_marks }}
                                                                            @endif
                                                                        </td>
                                                                        @if (@generalSetting()->result_type == 'mark')
                                                                            <td>
                                                                                @php
                                                                                    $totalMark = subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id));
                                                                                    $passMark = $mark->subject->pass_mark;
                                                                                @endphp
                                                                                @if ($passMark <= $totalMark)
                                                                                    @lang('exam.pass')
                                                                                @else
                                                                                    @lang('exam.fail')
                                                                                @endif
                                                                            </td>
                                                                        @else
                                                                            <td>
                                                                                {{ @$mark->total_gpa_grade }}
                                                                            </td>
                                                                            <td>
                                                                                {{ number_format(@$mark->total_gpa_point, 2, '.', '') }}
                                                                            </td>
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
    
    
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>@lang('exam.position'):
                                                                        {{ getStudentMeritPosition($record->class_id, $record->section_id, $exam->id, $record->id) }}
                                                                    </th>
                                                                    <th>
                                                                        @lang('exam.grand_total'):
                                                                        {{ $grand_total }}/{{ $grand_total_marks }}
                                                                    </th>
                                                                    @if (@generalSetting()->result_type == 'mark')
                                                                        <th></th>
                                                                    @else
                                                                        <th>@lang('exam.grade'):
                                                                            @php
                                                                                if (in_array($failgpaname->grade_name, $temp_grade)) {
                                                                                    echo $failgpaname->grade_name;
                                                                                } else {
                                                                                    $final_gpa_point = $total_gpa_point > 0 && $optional_gpa > 0 ? ($total_gpa_point - $optional_gpa) / ($total_subject - $optional_subject):0;
                                                                                    $average_grade = 0;
                                                                                    $average_grade_max = 0;
                                                                                    if ($result == 0 && $grand_total_marks != 0) {
                                                                                        $gpa_point = number_format($final_gpa_point, 2, '.', '');
                                                                                        if ($gpa_point >= $maxgpa) {
                                                                                            $average_grade_max = App\SmMarksGrade::where('school_id', Auth::user()->school_id)
                                                                                                ->where('academic_id', getAcademicId())
                                                                                                ->where('from', '<=', $maxgpa)
                                                                                                ->where('up', '>=', $maxgpa)
                                                                                                ->first('grade_name');
    
                                                                                            echo @$average_grade_max->grade_name;
                                                                                        } else {
                                                                                            $average_grade = App\SmMarksGrade::where('school_id', Auth::user()->school_id)
                                                                                                ->where('academic_id', getAcademicId())
                                                                                                ->where('from', '<=', $final_gpa_point)
                                                                                                ->where('up', '>=', $final_gpa_point)
                                                                                                ->first('grade_name');
                                                                                            echo @$average_grade->grade_name;
                                                                                        }
                                                                                    } else {
                                                                                        echo $failgpaname->grade_name;
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                        </th>
                                                                        <th>
                                                                            @lang('exam.gpa')
                                                                            @php
                                                                                $final_gpa_point = 0;
                                                                                $final_gpa_point = $total_gpa_point > 0 && $optional_gpa > 0 ?  ($total_gpa_point - $optional_gpa) / ($total_subject - $optional_subject):0;
                                                                                $float_final_gpa_point = number_format($final_gpa_point, 2);
                                                                                if ($float_final_gpa_point >= $maxgpa) {
                                                                                    echo $maxgpa;
                                                                                } else {
                                                                                    echo $float_final_gpa_point;
                                                                                }
                                                                            @endphp
                                                                        </th>
                                                                    @endif
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        </x-table>
                                                    @endif
                                                @endisset
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!-- End Exam Tab -->
                        @if (moduleStatusCheck('University'))
                            <div role="tabpanel" class="tab-pane fade" id="studentPanelExamTranscript">
                                @includeIf('university::exam.partials._examTabView')
                            </div>
                            <script src="{{ url('Modules\University\Resources\assets\js\app.js') }}"></script>
                        @endif
                        <!-- Start Online Exam Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="studentOnlineExam">
                            @php
                                $exam_count = count($exam_terms);
                            @endphp
    
                            <div class="no-search no-paginate no-table-info mb-2">
                                @if ($result_views->count() < 1)
                                    <h4 class="text-center">@lang('exam.result_not_publish_yet')</h4>
                                @endif
                                {{-- @foreach ($result_views as $exam) --}}
                                <table id="table_id" class="table" cellspacing="0" width="100%">
    
                                    <thead>
                                        <tr>
                                            <th>@lang('common.title')</th>
                                            <th>@lang('common.time')</th>
                                            <th>@lang('exam.total_marks')</th>
                                            <th>@lang('exam.obtained_marks') </th>
                                            <th>@lang('reports.result')</th>
                                            <th>@lang('common.status')</th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @foreach ($result_views as $result_view)
                                            <tr>
                                                <td>{{ $result_view->onlineExam != '' ? @$result_view->onlineExam->title : '' }}
                                                </td>
                                                <td data-sort="{{ strtotime(@$result_view->onlineExam->date) }}">
                                                    @if (!empty(@$result_view->onlineExam))
                                                        {{ @$result_view->onlineExam->date != '' ? dateConvert(@$result_view->onlineExam->date) : '' }}
    
    
                                                        <br> @lang('common.time'):
                                                        {{ @$result_view->onlineExam->start_time . ' - ' . @$result_view->onlineExam->end_time }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $total_marks = 0;
                                                        foreach ($result_view->onlineExam->assignQuestions as $assignQuestion) {
                                                            @$total_marks = $total_marks + @$assignQuestion->questionBank->marks;
                                                        }
                                                        echo @$total_marks;
                                                    @endphp
                                                </td>
                                                <td>{{ @$result_view->total_marks }}</td>
                                                <td>
                                                    @php
                                                        @$result = (@$result_view->total_marks * 100) / @$total_marks;
                                                        if (@$result >= @$result_view->onlineExam->percentage) {
                                                            echo 'Pass';
                                                        } else {
                                                            echo 'Fail';
                                                        }
                                                    @endphp
                                                </td>
    
                                                <td>
                                                    @php
                                                        $startTime = strtotime($result_view->onlineExam->date . ' ' . $result_view->onlineExam->start_time);
                                                        $endTime = strtotime($result_view->onlineExam->date . ' ' . $result_view->onlineExam->end_time);
                                                        $now = date('h:i:s');
                                                        $now = strtotime('now');
                                                    @endphp
                                                    @if ($now >= $endTime)
                                                        <a class="linkopenmodal btn btn-success modalLink" data-modal-size="modal-lg"
                                                            title="Answer Script"
                                                            href="
                                                    @if (moduleStatusCheck('OnlineExam')) {{ route('om-student_answer_script-modal', [@$result_view->online_exam_id, @$result_view->student_id, @$result_view->student_record_id]) }}
                                                    @else
                                                    {{ route('student_answer_script', [@$result_view->online_exam_id, @$result_view->student_id]) }} @endif
                                                    ">@lang('exam.answer_script')</a>
                                                    @else
                                                        <span class="btn primary-btn small  fix-gr-bg"
                                                            style="background:blue">@lang('exam.Wait_Till_Exam_Finish')</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- @endforeach --}}
                            </div>
                        </div>
                        <!-- End Online Exam Tab -->
    
                        <!-- Start Documents Tab -->
                        <div role="tabpanel"
                            class="tab-pane fade {{ Session::get('studentDocuments') == 'active' ? 'show active' : '' }}"
                            id="studentDocuments">
                            <div>
                                <div class="text-right mb-20">
                                    @if (userPermission('student_upload_document'))
                                        <button type="button" data-toggle="modal" data-target="#add_document_madal"
                                            class="primary-btn tr-bg text-uppercase bord-rad">
                                            @lang('student.upload_document')
                                            <span class="pl ti-upload"></span>
                                        </button>
                                    @endif
                                </div>
                                <div class="table-responsive">
                                <table id="" class="table simple-table school-table"
                                    cellspacing="0">
                                    <thead>
                                        <tr >
                                            <th>@lang('student.title')</th>
                                            <th>@lang('student.name')</th>
                                            <th>@lang('student.action')</th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @if (is_show('document_file_1'))
                                            @if ($student_detail->document_file_1 != '')
                                                <tr>
                                                    <td>{{ $student_detail->document_title_1 }}</td>
                                                    <td>{{ showDocument(@$student_detail->document_file_1) }}
                                                    </td>
                                                    <td>
                                                        @if (file_exists($student_detail->document_file_1))
                                                            
                                                            @if(userPermission('student_upload_document_download'))
                                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                                    href="{{ url($student_detail->document_file_1) }}" download>
                                                                    @lang('common.download')<span class="pl ti-download"></span> 
                                                                </a>
                                                            @endif   

                                                            @if(userPermission('delete_document'))
                                                                <a class="primary-btn icon-only fix-gr-bg"
                                                                    onclick="deleteDoc({{ $student_detail->id }},1)"
                                                                    data-id="1" href="#">
                                                                    <span class="ti-trash"></span>
                                                                </a>
                                                            @endif

                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        @if (is_show('document_file_2'))
                                            @if ($student_detail->document_file_2 != '')
                                                <tr>
                                                    <td>{{ $student_detail->document_title_2 }}</td>
                                                    <td>{{ showDocument(@$student_detail->document_file_2) }}
                                                    </td>
                                                    <td>
                                                        @if (file_exists($student_detail->document_file_2))
                                                            @if(userPermission('student_upload_document_download'))
                                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                                    href="{{ url($student_detail->document_file_2) }}" download>
                                                                    @lang('common.download')<span class="pl ti-download"></span>
                                                                </a>
                                                            @endif 
                                                             @if(userPermission('delete_document'))
                                                            <a class="primary-btn icon-only fix-gr-bg"
                                                                onclick="deleteDoc({{ $student_detail->id }},2)"
                                                                data-id="2" href="#">
                                                                <span class="ti-trash"></span>
                                                            </a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        @if (is_show('document_file_3'))
                                            @if ($student_detail->document_file_3 != '')
                                                <tr>
                                                    <td>{{ $student_detail->document_title_3 }}</td>
                                                    <td>{{ showDocument(@$student_detail->document_file_3) }}
                                                    </td>
                                                    <td>
                                                        @if (file_exists($student_detail->document_file_3))
                                                            @if(userPermission('student_upload_document_download'))
                                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                                    href="{{ url($student_detail->document_file_3) }}" download>
                                                                    @lang('common.download')<span class="pl ti-download"></span>
                                                                </a>
                                                            @endif
                                                            
                                                            @if(userPermission('delete_document'))
                                                                <a class="primary-btn icon-only fix-gr-bg"
                                                                    onclick="deleteDoc({{ $student_detail->id }},3)"
                                                                    data-id="3" href="#">
                                                                    <span class="ti-trash"></span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        @if (is_show('document_file_4'))
                                            @if ($student_detail->document_file_4 != '')
                                                <tr>
                                                    <td>{{ $student_detail->document_title_4 }}</td>
                                                    <td>{{ showDocument(@$student_detail->document_file_4) }}
                                                    </td>
                                                    <td>
                                                        @if (file_exists($student_detail->document_file_4))
                                                            @if(userPermission('student_upload_document_download'))
                                                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                                    href="{{ url($student_detail->document_file_4) }}" download>
                                                                    @lang('common.download')<span class="pl ti-download"></span>
                                                                </a>
                                                            @endif
                                                            
                                                            @if(userPermission('delete_document'))
                                                                <a class="primary-btn icon-only fix-gr-bg"
                                                                    onclick="deleteDoc({{ $student_detail->id }},4)"
                                                                    data-id="4" href="#">
                                                                    <span class="ti-trash"></span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
    
                                        <div class="modal fade admin-query" id="delete-doc">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('common.delete')</h4>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>
    
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
    
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <form action="{{ route('student_document_delete') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="student_id">
                                                                <input type="hidden" name="doc_id">
                                                                <button type="button" class="primary-btn tr-bg"
                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                                <button type="submit"
                                                                    class="primary-btn fix-gr-bg">@lang('common.delete')</button>
    
                                                            </form>
    
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>
                                        </div>
    
                                        @foreach ($student_detail->studentDocument as $document)
                                            <tr class="d-flex">
                                                <td class="col-2">{{ $document->title }}</td>
                                                <td class="col-6">{{ showDocument($document->file) }}</td>
                                                <td class="col-4">
                                                    @if (file_exists($document->file))
                                                      @if(userPermission('student_upload_document_download'))
                                                        <a class="primary-btn tr-bg text-uppercase bord-rad"
                                                            href="{{ url($document->file) }}" download>
                                                            @lang('common.download')<span class="pl ti-download"></span>
                                                        </a>
                                                      @endif
                                                    @endif
                                                    
                                                    @if(userPermission('delete_document'))
                                                        <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                                            data-target="#deleteDocumentModal{{ $document->id }}"
                                                            href="#">
                                                            <span class="ti-trash"></span>
                                                        </a>
                                                    @endif
    
                                                </td>
                                            </tr>
                                            <div class="modal fade admin-query" id="deleteDocumentModal{{ $document->id }}">
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
                                                                    href="{{ route('student-document-delete', [$document->id]) }}">
                                                                    @lang('common.delete')</a>
                                                            </div>
                                                        </div>
    
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                        <!-- End Documents Tab -->
    
                        <!-- Start Documents Tab -->
                        @if (moduleStatusCheck('University'))
                            <div role="tabpanel"
                                class="tab-pane fade {{ Session::get('chooseSubject') == 'active' ? 'show active' : '' }}"
                                id="chooseSubject">
                                <div>
                                    @if ($canChoose == true)
                                        @include('backEnd.studentPanel.request_to_subject')
                                    @else
                                        <strong>{{ __('university::un.sorry_time_out') }}</strong>
                                    @endif
    
                                </div>
                            </div>
                        @endif
                        <!-- End Documents Tab -->
                        <!-- Add Document modal form start-->
                        <div class="modal fade admin-query" id="add_document_madal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('student.upload_document')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            {{ html()->form('POST', route('student_upload_document'))->attributes([
                                                'class' => 'form-horizontal',
                                                'files' => true,
                                                'enctype' => 'multipart/form-data',
                                                'name' => 'document_upload',
                                            ])->open() }}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="student_id"
                                                        value="{{ @$student_detail->id }}">
                                                    <div class="row mt-25">
                                                        <div class="col-lg-12">
                                                            <div class="primary_input">
                                                                <label>Title <span class="text-danger"> *</span> </label>
                                                                <input class="primary_input_field form-control{"
                                                                    type="text" name="title" value=""
                                                                    id="title">
    
    
                                                                <span class=" text-danger" role="alert" id="amount_error">
    
                                                                </span>
    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-30">
                                                    <div class="row no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="primary_input">
                                                                <input class="primary_input_field" type="text"
                                                                    id="placeholderPhoto" placeholder="Document" disabled>
    
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button class="primary-btn-small-input" type="button">
                                                                <label class="primary-btn small fix-gr-bg"
                                                                    for="upload_content_file">browse</label>
                                                                <input type="file" class="d-none" name="photo"
                                                                    id="upload_content_file">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
    
    
                                                <!-- <div class="col-lg-12 text-center mt-40">
                                                                        <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                                                            <span class="ti-check"></span>
                                                                            save information
                                                                        </button>
                                                                    </div> -->
                                                <div class="col-lg-12 text-center mt-40">
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                            data-dismiss="modal">@lang('common.cancel')</button>
    
                                                        <button class="primary-btn fix-gr-bg" type="submit">save</button>
                                                    </div>
                                                </div>
                                            </div>
                                            {{ html()->form()->close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add Document modal form end-->
    
                        <!-- Start Timeline Tab -->
                        <div role="tabpanel"
                            class="tab-pane fade {{ Session::get('studentTimeline') == 'active' ? 'show active' : '' }}"
                            id="studentTimeline">
                            <div>
    
                                <div class="table-responsive">
                                <table id="" class="table simple-table school-table"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            @if (moduleStatusCheck('University'))
                                                <th>@lang('university::un.session')</th>
                                                <th>@lang('university::un.faculty_department')</th>
                                                <th>@lang('university::un.semester(label)')</th>
                                            @else
                                                <th>@lang('common.class')</th>
                                                <th>@lang('common.section')</th>
                                                @if(shiftEnable())
                                                <th>@lang('common.shift')</th>
                                                @endif
                                            @endif
    
                                            <th>@lang('student.id_number')</th>
    
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @foreach ($student_detail->studentRecords as $record)
                                            <tr>
                                                @if (moduleStatusCheck('University'))
                                                    <td>
                                                        {{ $record->unSession->name }}
                                                    </td>
                                                    <td>
                                                        {{ $record->unFaculty->name . '(' . $record->unDepartment->name . ')' }}
                                                        @if ($record->is_default)
                                                            <span class="badge fix-gr-bg">
                                                                {{ __('common.default') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $record->unSemester->name . '(' . $record->unSemesterLabel->name . ')' }}
                                                    </td>
                                                @else
                                                    <td>
    
                                                        {{ $record->class->class_name }}
                                                        @if ($record->is_default)
                                                            <span class="badge fix-gr-bg">
                                                                {{ __('common.default') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $record->section->section_name }}
                                                    </td>
                                                    @if(shiftEnable())
                                                    <td>
                                                        {{ $record->shift->name }}
                                                    </td>
                                                    @endif
                                                @endif
    
                                                <td>{{ $record->roll_no }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                        <!-- End Timeline Tab -->
    
                        <!-- Start Attendance Tab -->
                        @include('backEnd.studentPanel.inc._student_attendance_tab')
                        <!-- End Attendance Tab -->
    
                        <!-- Start Attendance Tab -->
                        @include('backEnd.studentPanel.inc._subject_attendance_tab')
                        <!-- End Attendance Tab -->
    
                        <!-- Start Behaviour Records Tab -->
                        @if (moduleStatusCheck('BehaviourRecords'))
                            @include('backEnd.studentInformation.inc._student_behaviour_record_tab')
                        @endif
                        <!-- End Behaviour Records Tab -->
    
                        @if (moduleStatusCheck('Wallet'))
                            <div role="tabpanel"
                                class="tab-pane fade {{ Session::get('studentWallet') == 'active' ? 'show active' : '' }}"
                                id="studentWallet">
                                <div>
                                    @include('wallet::_addWallet')
                                </div>
                            </div>
                        @endif
                        
                        <!-- Custom Behavior Tab Pane -->
                        <div role="tabpanel" class="tab-pane fade" id="customBehavior">
                            <div class="custom-dashboard-wrapper">
                                <div class="stats-grid-dash">
                                    <div class="stat-card-dash">
                                        <span class="stat-value">{{ $behaviors->where('behavior_type', 'good')->count() }}</span>
                                        <span class="stat-label">Good Conduct Events</span>
                                        <i class="fas fa-smile stat-icon" style="color: var(--green-dash)"></i>
                                    </div>
                                    <div class="stat-card-dash">
                                        <span class="stat-value">{{ $behaviors->where('behavior_type', 'average')->count() }}</span>
                                        <span class="stat-label">Average Conduct Events</span>
                                        <i class="fas fa-meh stat-icon" style="color: var(--orange-dash)"></i>
                                    </div>
                                    <div class="stat-card-dash">
                                        <span class="stat-value">{{ $behaviors->where('behavior_type', 'misbehavior')->count() }}</span>
                                        <span class="stat-label">Misbehavior Incidents</span>
                                        <i class="fas fa-frown stat-icon" style="color: var(--red-dash)"></i>
                                    </div>
                                </div>
                                
                                <div class="glass-card">
                                    <h3><i class="fas fa-heart text-danger"></i> Continuous Behavior Records & Discipline Monitoring</h3>
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
                        </div>
                        
                        <!-- Custom Activities Tab Pane -->
                        <div role="tabpanel" class="tab-pane fade" id="customActivities">
                            <div class="custom-dashboard-wrapper">
                                <div class="glass-card">
                                    <h3><i class="fas fa-futbol text-success"></i> Registered Activities & Extracurricular Interests</h3>
                                    @if($activities->count() > 0)
                                        <div class="grid-3-dash">
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
                                            <p class="text-muted">No activities or interests registered yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Achievements Tab Pane -->
                        <div role="tabpanel" class="tab-pane fade" id="customAchievements">
                            <div class="custom-dashboard-wrapper">
                                <div class="row mb-4">
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <div class="glass-card score-ring-container" style="height: 100%;">
                                            <div class="score-ring {{ $engagement_class }}">
                                                {{ $engagement_score }}
                                            </div>
                                            <div>
                                                <h4 style="margin: 0 0 4px; font-weight: 700; color: #ffffff;">My Engagement Score</h4>
                                                <p class="text-muted" style="margin: 0; font-size: 13px;">
                                                    Status: <strong class="{{ $engagement_class }}">{{ $engagement_status }}</strong>
                                                </p>
                                                <div class="progress-bar-dash" style="width: 200px;">
                                                    <div class="progress-fill-dash green" style="width: {{ $engagement_score }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <div class="glass-card" style="height: 100%;">
                                            <h4 style="margin: 0 0 10px; font-weight: 700; color: #ffffff;"><i class="fas fa-chart-line text-info"></i> How Engagement is Calculated</h4>
                                            <div style="font-size: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                                <div><i class="fas fa-check-circle text-success"></i> Good Conduct: <strong>+10 pts</strong></div>
                                                <div><i class="fas fa-check-circle text-success"></i> Registered Activities: <strong>+15 pts</strong></div>
                                                <div><i class="fas fa-check-circle text-success"></i> Verified Achievements: <strong>+25 pts</strong></div>
                                                <div><i class="fas fa-check-circle text-success"></i> Class Attendance: <strong>+2 pts / Day</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="glass-card">
                                    <h3><i class="fas fa-trophy text-warning"></i> Certified Academic & Sports Achievements</h3>
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
                        </div>
                        
                        <!-- Custom Library Tab Pane -->
                        <div role="tabpanel" class="tab-pane fade" id="customLibrary">
                            <div class="custom-dashboard-wrapper">
                                <div class="glass-card">
                                    <h3><i class="fas fa-book text-info"></i> Checked Out Books & Library Issue Log</h3>
                                    @if($library_issues->count() > 0)
                                        <div class="table-wrap-dash">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Book Title</th>
                                                        <th>Author</th>
                                                        <th>ISBN Number</th>
                                                        <th>Issued Date</th>
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
                                            <p class="text-muted">No book checkout records found.</p>
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
                                        <span class="stat-label">Canteen & bookstore Spending Summary</span>
                                        <i class="fas fa-utensils stat-icon" style="color: var(--orange-dash)"></i>
                                    </div>
                                </div>
                                
                                <div class="glass-card">
                                    <h3><i class="fas fa-receipt text-warning"></i> My Canteen Purchases & Personal Billing Records</h3>
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
                                            <p class="text-muted">No personal spending or canteen purchase logs found.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Communication Tab Pane -->
                        <div role="tabpanel" class="tab-pane fade" id="customComms">
                            <div class="custom-dashboard-wrapper">
                                <div class="glass-card">
                                    <h3><i class="fas fa-envelope-open-text text-primary"></i> School Notification & Special Event Broadcasts</h3>
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
                                                        <p style="margin-top: 4px; margin-bottom: 2px; color: var(--text-dash)">{{ $cm->message }}</p>
                                                        <small class="text-muted">Type: <strong>{{ ucfirst($cm->event_type) }}</strong> | Dispatched By: {{ $cm->sent_by }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                            <p class="text-muted">No notifications or event broadcasts received yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
            <!-- End Student Details -->
        </div>
        </div>
    </section>

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
                                <input type="hidden" name="student_id" value="{{ @$student_detail->id }}">
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <input class="primary_input_field form-control{" type="text"
                                                name="title" value="" id="title">
                                            <label>Title <span class="text-danger"> *</span> </label>


                                            <span class=" text-danger" role="alert" id="amount_error">

                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input
                                                class="primary_input_field  primary_input_field date form-control form-control"
                                                id="startDate" type="text" name="date">
                                            <label class="primary_input_label" for="">@lang('common.date')</label>

                                        </div>
                                    </div>
                                    <button class="" type="button">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="primary_input">
                                    <textarea class="primary_input_field form-control" cols="0" rows="3" name="description"
                                        id="Description"></textarea>
                                    <label>Description<span></span> </label>

                                </div>
                            </div>

                            <div class="col-lg-12 mt-30">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text"
                                                id="placeholderFileFourName" placeholder="Document" disabled>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg"
                                                for="document_file_4">browse</label>
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


                            <!-- <div class="col-lg-12 text-center mt-40">
                                            <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                                <span class="ti-check"></span>
                                                save information
                                            </button>
                                        </div> -->
                            <div class="col-lg-12 text-center mt-40">
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                        data-dismiss="modal">@lang('common.cancel')</button>

                                    <button class="primary-btn fix-gr-bg" type="submit">save</button>
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
    {{-- @include('backEnd.partials.data_table_js') --}}
    @include('backEnd.partials.date_picker_css_js')
    <script>
        // data table responsive problem tab
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });

        function deleteDoc(id, doc) {
            var modal = $('#delete-doc');
            modal.find('input[name=student_id]').val(id)
            modal.find('input[name=doc_id]').val(doc)
            modal.modal('show');
        }
    </script>
@endsection
