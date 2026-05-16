
 @if(Auth::user()->role_id == 1 )
    <div class="col-lg-9">
 @elseif(userPermission(817) && userPermission(818))
    <div class="col-lg-9">
 @else
    <div class="col-lg-12">
 @endif

    <div class="main-title">
        <h3 class="mb-0">
             @lang('common.virtual_class_list')
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
               <thead>
                    <tr>
                    <tr>
                        <th>#</th>
                             @if (Auth::user()->role_id != 2)
                        <th>@lang('common.class')</th>
                        <th>@lang('common.class_section')</th>
                         @endif
                        <th>@lang('common.meeting_id')</th>                 
                         <th>@lang('common.topic')</th>
                        <th>@lang('common.date') | @lang('common.time')</th>                        
                        <th>@lang('common.duration')</th>
                        <th>@lang('common.start_join')</th>
                        <th>@lang('common.start_join_before')</th>
                        <th>@lang('common.actions')</th>
                    </tr>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                            $virtual_classs = in_array(auth()->user()->role_id, [2,3]) ? $record->student_jitsi_virtual_class  : $virtual_classs;
                        @endphp
                 @foreach($virtual_classs as $key => $meeting )
                        <tr>
                            <td>{{ $key + 1 }}</td>
                              @if (Auth::user()->role_id != 2)
                                <td>{{ $meeting->class->class_name }}</td>
                              
                                <td>{{ $meeting->section_id !=null ?  $meeting->section->section_name :'All sections' }}</td>

                            @endif
                            <td>{{ $meeting->meeting_id }}</td>
                            <td>{{ $meeting->topic }}</td>
                            <td>{{ $meeting->date }} | {{ $meeting->time }}</td>
                            
                            <td> @if($meeting->duration==0) Unlimited @else {{ $meeting->duration }} @endif Min</td>
                             <td> @if($meeting->time_start_before==null) 10  @else {{ $meeting->time_start_before }} @endif Min</td>
                            <td>
                                @php
                                $created_id=  $meeting->created_by;
                               $user= App\User::find($created_id);
                                if($user->role_id==1){
                                   $teahcer_id=DB::table('jitsi_virtual_class_teachers')->where('meeting_id',$meeting->id)->first(['id','user_id']);
                                    if(!is_null($teahcer_id)) { 
                                        $teahcer_id=$teahcer_id->user_id;
                                    }
                                    }else{
                                        $teahcer_id=0;
                                    
                                    }
                             @endphp
                                @if($meeting->getCurrentStatusAttribute() == 'started')

                                 @if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id==Auth::user()->id)
                                <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('jitsi.class.start', $meeting->meeting_id) }}">


                                        @lang('common.start')
                                        </a>
                                    @else
                                     
                                       <a target="_blank" class="primary-btn small bg-success text-white border-0" href="{{ route('jitsi.class.join', $meeting->meeting_id) }}">  @lang('common.join') </a>
                                     
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

                                        @if(Auth::user()->role_id == 1 )
                                        <a class="dropdown-item"
                                        href="{{ route('jitsi.virtual-class.show', $meeting->id) }}">@lang('common.view')</a>
                                        
                                     <a class="dropdown-item"
                                        href="{{ route('jitsi.virtual-class.edit',$meeting->id )}}">@lang('common.edit')</a>

                                     <a class="dropdown-item" data-toggle="modal"
                                        data-target="#d{{$meeting->id}}"
                                        href="#">@lang('common.delete')</a>
                                     @elseif(userPermission(817) && userPermission(818))
                                     <a class="dropdown-item"
                                     href="{{ route('jitsi.virtual-class.show', $meeting->id) }}">@lang('common.view')</a>
                                     
                                  <a class="dropdown-item"
                                     href="{{ route('jitsi.virtual-class.edit',$meeting->id )}}">@lang('common.edit')</a>

                                  <a class="dropdown-item" data-toggle="modal"
                                     data-target="#d{{$meeting->id}}"
                                     href="#">@lang('common.delete')</a>
                                     @else
                                     <a class="dropdown-item"
                                     href="{{ route('jitsi.virtual-class.show', $meeting->id) }}">@lang('common.view')</a>
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
                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                            <form class="" action="{{ route('jitsi.virtual_class.destroy',$meeting->id) }}"
                                                  method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="primary-btn fix-gr-bg"
                                                        type="submit">@lang('common.delete')</button>
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