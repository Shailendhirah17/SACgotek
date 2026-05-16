@extends('backEnd.master')
@section('title') 
    @lang('common.class_report')
@endsection

@section('css')
<style>
    .propertiesname{
        text-transform: uppercase;
    }.
    .recurrence-section-hide {
       display: none!important
    }
    </style>
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.class_reports') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('jitsi::jitsi.jitsi')</a>
                <a href="#">@lang('common.class_reports')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area">
    <div class="container-fluid p-0">

        <div class="row">
            <div class="col-lg-10">
                <h3 class="mb-30">
                    @lang('common.virtual_class_reports')
                </h3>
            </div>
        </div>
        <div class="row mb-20">
            <div class="col-lg-12">
              @php
                $div =  Auth::user()->role_id == 1 ? 'col-lg-2 mt-30-md' : 'col-lg-3 mt-30-md';
              @endphp

                <div class="white-box">
                    <form action="{{ route('jitsi.virtual.class.reports.show') }}" method="GET">
                            <div class="row">
                                <div class="{{ $div }}">
                                    <select class="w-100 niceSelect bb form-control {{ $errors->has('class_id') ? ' is-invalid' : '' }}" id="select_class" name="class_id">
                                        <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')</option>
                                        @foreach($classes as $class)
                                            @if (isset($class_id) )
                                                <option value="{{$class->id}}" {{ $class_id == $class->id? 'selected':'' }}>{{$class->class_name}}</option>
                                            @else
                                                <option value="{{$class->id}}" >{{$class->class_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="{{ $div }}" id="select_section_div">
                                    <select class="w-100 niceSelect bb form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}" id="select_section" name="section_id">
                                        <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                    </select>
                                    <div class="pull-right loader" id="select_section_loader" style="margin-top: -30px;padding-right: 21px;">
                                                    <img src="{{asset('Modules/Lesson/Resources/assets/images/pre-loader.gif')}}" alt="" style="width: 28px;height:28px;">
                                                </div> 
                                </div>
                                @if(Auth::user()->role_id == 1)
                                    <div class="col-lg-2 mt-30-md">
                                        <select class="w-100 niceSelect bb form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}" name="teachser_ids">
                                            <option data-display="@lang('common.select_teacher')" value="">@lang('common.select_teacher')</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{$teacher->id }}" {{ isset($teachser_ids) == $teacher->id? 'selected':'' }} >{{$teacher->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-3 mt-30-md">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input date form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}" id="startDate" type="text"
                                                    name="from_time" autocomplete="off" value="{{ isset($from_time) ? Carbon\Carbon::parse($from_time)->format('m/d/Y') : '' }}">
                                                <label for="startDate">@lang('common.from_date')</label>
                                                <span class="focus-border"></span>
                                                
                                                @if ($errors->has('from_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('from_time') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    
                                </div>
                                <div class="col-lg-3 mt-30-md">

                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input date form-control{{ $errors->has('to_time') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                     name="to_time" value="{{ isset($to_time) ? Carbon\Carbon::parse($to_time)->format('m/d/Y') : '' }}" readonly>
                                                    <label>@lang('common.to_date')</label>
                                                    <span class="focus-border"></span>
                                                @if ($errors->has('to_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('to_time') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg" data-toggle="tooltip" title="">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area" style="display:  {{ isset($meetings) ? 'block' : 'none'  }} ">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="default_table2" class="display school-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                        <th>@lang('common.class')</th>
                                        <th>@lang('common.class_section')</th>
                                    @endif
                                    <th>@lang('common.meeting_id')</th>
                                    <!--<th>@lang('common.password')</th>-->
                                    <th>@lang('common.topic')</th>
                                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                        <th>@lang('common.teachers')</th>
                                    @endif
                                    <th>@lang('common.date')</th>
                                    <th>@lang('common.time')</th>
                                    <th>@lang('common.duration')</th>
                                </tr>
                        </thead>

                        <tbody>
                            @if (isset($meetings))
                                @foreach($meetings as $key => $meeting )
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                        <td>{{ $meeting->class->class_name }}</td>
                                      
                                         <td>
                                             {{ $meeting->section_id !=null ?  $meeting->section->section_name :'All sections' }}
                                       </td>
                                    @endif
                                    <td>{{ $meeting->meeting_id }}</td>
                                    <!--<td>{{ $meeting->attendee_password }}</td>-->
                                    <td>{{ $meeting->topic }}  </td>
                                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                        <td>{{ $meeting->teachersName }}</td>
                                    @endif
                                    <td>{{ $meeting->date }}</td>
                                    <td>{{ $meeting->time }}</td>
                                    <td>@if($meeting->duration==0) Unlimited @else {{ $meeting->duration }} @endif min</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

        </div>
    </div>
</section>
@endsection
