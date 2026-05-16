@if(Auth::user()->role_id == 1 )
    <div class="col-lg-9">
@elseif(userPermission(823) && userPermission(822))
    <div class="col-lg-9">
@else
    <div class="col-lg-12">
@endif
<div class="main-title">
    <h3 class="mb-0">
        @lang('jitsi::jitsi.meeting_list')
    </h3>
</div>

    <div class="row">
        <div class="col-lg-12">
            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('jitsi::jitsi.meeting_id')</th>                  
                        <th>@lang('jitsi::jitsi.topic')</th>
                        <th>@lang('jitsi::jitsi.date_|_time')</th>                      
                        <th>@lang('common.duration')</th>
                        <th>@lang('jitsi::jitsi.start_join_before')</th>

                        <th>@lang('jitsi::jitsi.start_join')</th>
                        <th>@lang('common.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meetings as $key => $meeting )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $meeting->meeting_id }}</td>
                            <td>{{ $meeting->topic }}</td>
                            <td>{{ $meeting->date }} | {{ $meeting->time }}</td>                         
                            <td> @if($meeting->duration==0) Unlimited @else {{ $meeting->duration }} @endif Min</td>
                            <td> @if($meeting->time_start_before==null) 10 @else {{ $meeting->time_start_before }} @endif Min</td>
                            <td>
                                @if($meeting->getCurrentStatusAttribute() == 'started')
                                    @if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by)
                                        <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('jitsi.meeting.start', $meeting->meeting_id) }}">
                                            @lang('common.start')
                                        </a>
                                    @else
                                        <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('jitsi.meeting.join', $meeting->meeting_id) }}">
                                            @lang('common.join') 
                                        </a>
                                    @endif
                                @elseif( $meeting->getCurrentStatusAttribute() == 'waiting')
                                    <a href="#Closed" class="primary-btn small bg-info text-white border-0">@lang('common.waiting')</button>
                                @else
                                    <a href="#Closed" class="primary-btn small bg-warning text-white border-0">@lang('common.closed')</button>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                        @lang('common.select')
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ route('jitsi.meetings.show', $meeting->id) }}">
                                            @lang('common.view')
                                        </a>
                                    @if(userPermission(824))
                                        <a class="dropdown-item" href="{{ route('jitsi.meetings.edit',$meeting->id )}}">
                                            @lang('common.edit')
                                        </a>
                                    @endif
                                    @if(userPermission(825))
                                        <a class="dropdown-item" data-toggle="modal" data-target="#d{{$meeting->id}}" href="#">
                                            @lang('common.delete')
                                        </a>
                                    @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade admin-query" id="d{{$meeting->id}}">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('common.delete')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center">
                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                        </div>
                                        <div class="mt-40 d-flex justify-content-between">
                                            <button type="button" class="primary-btn tr-bg"
                                                    data-dismiss="modal">@lang('common.cancel')
                                                </button>
                                            <form class="" action="{{ route('jitsi.meetings.destroy',$meeting->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="primary-btn fix-gr-bg" type="submit">
                                                    @lang('common.delete')
                                                </button>
                                            </form>
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