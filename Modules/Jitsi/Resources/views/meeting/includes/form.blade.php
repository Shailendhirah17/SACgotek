@if (in_array(823, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
<div class="col-lg-3">

    <div class="main-title">
        <h3 class="mb-20">
            @if(isset($editdata))
            @lang('common.edit_meeting')
            @else
            @lang('common.add_meeting')  
            @endif
           
        </h3>
    </div>

    @if(isset($editdata))
        <form class="form-horizontal" action="{{ route('jitsi.meetings.update',$editdata->id) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @else

                <form class="form-horizontal" action="{{ route('jitsi.meetings.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    @endif
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">


                    <div class="row mb-30">
                        <div class="col-lg-12 ">
                            <select class="niceSelect w-100 bb user_type form-control{{ $errors->has('member_type') ? ' is-invalid' : '' }}" name="member_type">
                                <option data-display=" @lang('jitsi::jitsi.member_type') *" value="">@lang('jitsi::jitsi.member_type') *</option>
                                @foreach($roles as $value)
                                    @if(isset($editdata))
                                        <option value="{{$value->id}}" {{ $value->id == $user_type ? 'selected' : '' }}>{{ $value->name }}</option>
                                       
                                    @else
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('member_type'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('member_type') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-40">
                        <div class="col-lg-12" id="selectTeacherDiv">
                            <label for="checkbox" class="mb-2">@lang('common.member') *</label>
                                <select multiple id="selectSectionss" {{ @$errors->has('participate_ids') ? ' is-invalid' : '' }} name="participate_ids[]" style="width:300px">
                                    @if(isset($editdata))
                                        @foreach ($userList as $value)
                                            <option value="{{$value->id}}"  selected >{{ $value->full_name }}</option>
                                            {{-- <option value="{{$value->id}}" @if (in_array($value->id,[13,17])) selected @endif >{{ $value->name }}</option> --}}
                                        {{-- <option value="{{$value->id}}" {{ $value->role_id == $user_type ? 'selected' : '' }} >{{$value->full_name}}</option> --}}
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('participate_ids'))
                                    <span class="invalid-feedback" role="alert" style="display:block">
                                        <strong>{{ $errors->first('participate_ids') }}</strong>
                                    </span>
                                @endif
                        </div>
                    </div>


                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <input
                                                class="primary-input form-control{{ $errors->has('topic') ? ' is-invalid' : '' }}"
                                                type="text" name="topic" autocomplete="off"
                                                value="{{ isset($editdata) ?  old('topic',$editdata->topic) : old('topic') }}">
                                            <label>@lang('jitsi::jitsi.topic') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('topic'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('topic') }}</strong>
                                              </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                  <div class="row mt-40">
                                      <div class="col-lg-12">
                                          <div class="input-effect">
                                              <textarea class="primary-input form-control" cols="0" rows="4"
                                                        name="description"
                                                        id="address">{{isset($editdata) ? old('description',$editdata->description) : old('description')}}</textarea>
                                              <label>@lang('common.description')</label>
                                              <span class="focus-border textarea"></span>
                                              @if ($errors->has('description'))
                                                  <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('description') }}</strong>
                                      </span>
                                              @endif
                                          </div>
                                      </div>
                                  </div>


                               

                                <div class="row mt-40">
                                    <div class="col-lg-6">
                                        <label>@lang('jitsi::jitsi.date_of_meeting')<span>*</span></label>
                                        <input class="primary-input date form-control" id="startDate" type="text"
                                               name="date" readonly="true"
                                               value="{{ isset($editdata) ? old('date',Carbon\Carbon::parse($editdata->date_of_meeting)->format('m/d/Y')): old('date',Carbon\Carbon::now()->format('m/d/Y'))}}"
                                               required>
                                        @if ($errors->has('date'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 no-gutters input-right-icon">
                                       
                                          <label>@lang('jitsi::jitsi.meeting_time')  <span>*</span></label>
                                        <input
                                            class="primary-input time form-control{{ @$errors->has('time') ? ' is-invalid' : '' }}"
                                            type="text" name="time"
                                            value="{{ isset($editdata) ? old('time',$editdata->time): old('time')}}">
                                        <span class="focus-border"></span>
                                        @if ($errors->has('time'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ @$errors->first('time') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <input oninput="numberCheckWithDot(this)" class="primary-input form-control{{ $errors->has('duration') ? ' is-invalid' : '' }}"
                                            type="text" name="duration" autocomplete="off" value="{{isset($editdata)? old('duration',$editdata->duration) : old('duration')}}">
                                            <label>@lang('jitsi::jitsi.meeting_duration')<span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('duration'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('duration') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                      
                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <input oninput="numberCheckWithDot(this)" class="primary-input form-control{{ $errors->has('time_start_before') ? ' is-invalid' : '' }}"
                                            type="text" name="time_start_before" autocomplete="off" value="{{isset($editdata)? old('time_start_before',$editdata->time_start_before) : 10 }}">
                                            <label>@lang('jitsi::jitsi.meeting_start_before') </label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('time_start_before'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('time_start_before') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                    <div class="row no-gutters input-right-icon mt-30">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input
                                                    class="primary-input form-control {{ $errors->has('attached_file') ? ' is-invalid' : '' }}"
                                                    readonly="true" type="text"
                                                    placeholder="{{isset($editdata->file) && @$editdata->file != ""? getFilePath3(@$editdata->file) : trans('common.attach_file')}}"
                                                    id="placeholderUploadContent">
                                                <span class="focus-border"></span>
                                                @if ($errors->has('attached_file'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('attached_file') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                    for="upload_content_file">@lang('common.browse')</label>
                                                <input type="file" class="d-none form-control" name="attached_file"
                                                    id="upload_content_file">
                                            </button>
                                        </div>
                                    </div>

                        


                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                  
                                            <button class="primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                @if(isset($editdata))
                                                    @lang('common.update')
                                                @else
                                                   @lang('common.save')
                                                @endif
                                               
                                            </button>
                                 
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
</div>

@endif

