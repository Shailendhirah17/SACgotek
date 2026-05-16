@extends('backEnd.master')
@section('title')
@lang('common.virtual_meeting')
@endsection
@section('mainContent')
    <style>
        .propertiesname {
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.virtual_meeting')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('jitsi::jitsi.jitsi')</a>
                    <a href="#">@lang('common.virtual_meeting')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="mb-30">@lang('common.topic')</h3>
                </div>
                <div class="col-md-2 pull-right  text-right">
                    @if($localMeetingData->created_by == auth()->user()->id || auth()->user()->role_id ==1)
                    <a href="{{ route('jitsi.meetings.edit', $localMeetingData->id) }}" class="primary-btn small fix-gr-bg ">
                        <span class="ti-pencil-alt"></span>
                        @lang('common.edit') 
                    </a>
                    @endif
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="" class="display school-table school-table-style" cellspacing="0" width="100%">
                                <tr>
                                    <th>#</th>
                                     <th>@lang('common.name')</th>
                                    <th>@lang('common.status')</th>
                                </tr>
                                @php $sl = 1 @endphp
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.topic')</td>
                                    <td>{{ $localMeetingData->topic }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.document')</td>
                                    <td>
                                        @if($localMeetingData->file)
                                        <a href="{{ asset($localMeetingData->file) }}" download="" ><i class="fa fa-download mr-1"></i> @lang('common.download')</a>
                                        @else 
                                        {{ __('common.No file') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.start_date_time') </td>
                                    <td>{{ $localMeetingData->date }} {{ $localMeetingData->time }}</td>
                                </tr>
                                <tr>
                                    <td> {{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.meeting_id')</td>
                                    <td>{{$localMeetingData->meeting_id}}</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td> 
                                    <td class="propertiesname">@lang('common.participants')</td> 
                                    <td> {{ $localMeetingData->participatesName }}</td>
                                </tr>
                                  <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.start_join')</td>
                                    <td>@lang('common.meeting_start_join_before') {{ $localMeetingData->time_start_before==null ? '10' :  $localMeetingData->time_start_before}} @lang('common.min')</td>
                                </tr>
                                <tr>
                                    <td>{{ $sl++ }} </td>
                                    <td class="propertiesname">@lang('common.join')</td>
                                    <td>
                                    @if($localMeetingData->getCurrentStatusAttribute() == 'started')
                                    @if (Auth::user()->role_id == 1 || Auth::user()->id == $localMeetingData->created_by)
                                    <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('jitsi.meeting.start', $localMeetingData->meeting_id) }}">
                                        @lang('common.start')
                                    </a>
                                    @else
                                    <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('jitsi.meeting.join', $localMeetingData->meeting_id) }}">
                                        @lang('common.join') 
                                    </a>
                                    @endif
                            @elseif( $localMeetingData->getCurrentStatusAttribute() == 'waiting')
                                <a href="#Closed" class="primary-btn small bg-info text-white border-0">@lang('common.waiting')</button>
                            @else
                                <a href="#Closed" class="primary-btn small bg-warning text-white border-0">@lang('common.closed')</button>
                            @endif
                            </td>
                            </tr>
                            <tr>
                                <td>{{ $sl++ }} </td>
                                <td class="propertiesname">@lang('common.duration')  </td>
                                <td>{{@$localMeetingData->duration}} @lang('common.min')</td>
                            </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
