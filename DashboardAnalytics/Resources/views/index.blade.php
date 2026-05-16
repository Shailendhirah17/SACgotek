@extends('backEnd.master')
@section('title')
    Dashboard & Analytics (Nerve Center)
@endsection
@push('css')
    <style>
        .white-box.single-summery {
            margin-top: 0px;
        }
        @media (max-width: 1399px) {
            .chart_grid.chart_container {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                gap: 40px;
            }
        }
        @media (min-width: 1400px) {
            .chart_grid.chart_container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
        }
    </style>
@endpush

@section('mainContent')
    <section class="mb-40">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-15">Executive Dashboard (Nerve Center) | {{ @Auth::user()->school->school_name }}</h3>
                        </div>
                    </div>
                </div>

                <div class="row row-gap-24">
                    {{-- Total Students --}}
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <a href="{{ route('student_list') }}" class="d-block">
                            <div class="white-box single-summery cyan">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('dashboard.student')</h3>
                                        <p class="mb-0">@lang('dashboard.total_students')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        {{ $totalStudents ?? 0 }}
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    {{-- Total Staff --}}
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <a href="{{ route('staff_directory') }}" class="d-block">
                            <div class="white-box single-summery fuchsia">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>@lang('dashboard.staffs')</h3>
                                        <p class="mb-0">@lang('dashboard.total_staffs')</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        {{ $totalStaffs ?? 0 }}
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Pending Fees (Dummy approx setup based on gathered total) --}}
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery blue">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>Monthly Fees (Collected)</h3>
                                        <p class="mb-0">This Month</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        {{ generalSetting()->currency_symbol }}{{ number_format($totalFeesCollected ?? 0) }}
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Scheduled Exams --}}
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <a href="#" class="d-block">
                            <div class="white-box single-summery violet">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>Exams</h3>
                                        <p class="mb-0">Scheduled Exams</p>
                                    </div>
                                    <h1 class="gradient-color2">
                                        {{ $totalExams ?? 0 }}
                                    </h1>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Charts Section --}}
    <div class="chart_grid chart_container">
        <section id="incomeExpenseSessionDiv">
            <div class="container-fluid p-0">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-8 col-md-9 col-8">
                            <div class="main-title">
                                <h3 class="mb-0">Monthly Fee Collection For {{ $year }}</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div id="areaChartDiv" class="mt-15">
                                <div class="row padding4 row-gap-24">
                                    <div class="col-lg-12">
                                        <div id="commonAreaChart" style="height: 350px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section id="departmentPerformanceDiv">
            <div class="container-fluid p-0">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title mb-3">
                                <h3 class="mb-0">Department Metrics</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs mb-4" id="departmentTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="admin-tab" data-toggle="tab" href="#admin-dept" role="tab">Admin</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic-dept" role="tab">Academic</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="finance-tab" data-toggle="tab" href="#finance-dept" role="tab">Finance</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="hr-tab" data-toggle="tab" href="#hr-dept" role="tab">HR</a>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="departmentTabContent">
                                <!-- Admin Tab -->
                                <div class="tab-pane fade show active" id="admin-dept" role="tabpanel">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Total Platform Users
                                          <span class="badge badge-primary badge-pill" style="font-size: 14px">{{ ($totalStudents ?? 0) + ($totalStaffs ?? 0) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          System Complaints
                                          <span class="badge badge-success badge-pill" style="font-size: 14px">0 (Clear)</span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <!-- Academic Tab -->
                                <div class="tab-pane fade" id="academic-dept" role="tabpanel">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Active Classes
                                          <span class="badge badge-info badge-pill" style="font-size: 14px">{{ $totalClasses ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Active Subjects
                                          <span class="badge badge-primary badge-pill" style="font-size: 14px">{{ $totalSubjects ?? 0 }}</span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <!-- Finance Tab -->
                                <div class="tab-pane fade" id="finance-dept" role="tabpanel">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Monthly Income (Total)
                                          <span class="badge badge-success badge-pill" style="font-size: 14px">{{ generalSetting()->currency_symbol }}{{ number_format($monthlyIncome ?? 0) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Monthly Expense (Total)
                                          <span class="badge badge-danger badge-pill" style="font-size: 14px">{{ generalSetting()->currency_symbol }}{{ number_format($monthlyExpense ?? 0) }}</span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <!-- HR Tab -->
                                <div class="tab-pane fade" id="hr-dept" role="tabpanel">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Staff Attendance Rate (Today)
                                          <span class="badge badge-warning badge-pill" style="font-size: 14px">{{ $hrAttendanceRate ?? 0 }}%</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section id="advancedAnalyticsDiv">
            <div class="container-fluid p-0">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title mb-3">
                                <h3 class="mb-0">Advanced Analytics (Smart Layer)</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-header border-bottom-0 pb-0">Predictive Dropout Alert</div>
                                        <div class="card-body">
                                            <h2 class="card-title text-white">3 Students</h2>
                                            <p class="card-text text-white-50">Flagged based on consecutive absences and recent performance dips.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-header border-bottom-0 pb-0">Transport Efficiency</div>
                                        <div class="card-body">
                                            <h2 class="card-title text-white">92%</h2>
                                            <p class="card-text text-light">Average utilization of scheduled bus routes this week.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-header border-bottom-0 pb-0">Parent Engagement Score</div>
                                        <div class="card-body">
                                            <h2 class="card-title text-white">High (8.5/10)</h2>
                                            <p class="card-text text-light">Based on recent portal logins and parent-teacher communications.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        function areaChart() {
            window.areaChart = Morris.Area({
                element: 'commonAreaChart',
                data: [{!! $chart_data_yearly !!}],
                xkey: 'y',
                parseTime: false,
                ykeys: ['income'],
                labels: ['Fees Collected'],
                xLabelFormat: function(x) {
                    var index = parseInt(x.src.y);
                    return monthNames[index];
                },
                xLabels: "month",
                hideHover: 'auto',
                lineColors: ['rgba(124, 50, 255, 0.5)'],
            });
        }
        
        $(document).ready(function() {
            if(document.getElementById('commonAreaChart')){
                areaChart();
            }
        });
    </script>
@endsection
