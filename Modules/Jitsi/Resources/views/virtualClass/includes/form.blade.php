@if (in_array(818, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 )
<div class="col-lg-3">

    <div class="main-title">
        <h3 class="mb-20">
            @if(isset($editdata))
            @lang('common.edit_virtual_class')
            @else
            @lang('common.add_virtual_class')  
            @endif
            
        </h3>
    </div>

    @if(isset($editdata))
        <form class="form-horizontal" action="{{ route('jitsi.virtual-class.update',$editdata->id) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @else

                <form class="form-horizontal" action="{{ route('jitsi.virtual-class.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    @endif
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">



                                <div class="row ">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <select 
                                                    class="niceSelect w-100 bb  form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                                                    name="class_id" id="select_class">
                                                <option data-display="  @lang('common.class')*"
                                                        value="">@lang('common.class') *
                                                </option>


                                                @foreach($classes as $class)
                                                    @if (isset($editdata))
                                                        <option value="{{$class->id}}" {{ old('class_id',$editdata->class_id) == $class->id  ? 'selected':''}}> {{$class->class_name}}</option>
                                                    @else
                                                        <option value="{{$class->id}}"  {{ old('class_id') ==  $class->id   ? 'selected':''}} >   {{$class->class_name}}</option>
                                                    @endif

                                                @endforeach

                                            </select>
                                            @if ($errors->has('class_id'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('class_id') }}</strong>
                                              </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            

                        <div class="row  mt-40">
                            <div class="col-lg-12" id="select_section_div">
                                <select class="w-100 bb niceSelect form-control {{ @$errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                <option data-display="@lang('common.select_section') " value="">@lang('common.select_section') </option>
                                @if(isset($editdata))
                                    @foreach($class_sections as $section)
                                        <option value="{{ @$section->id }}" {{ old('section',$section->id) == $editdata->section_id ? 'selected':''}}>{{ @$section->section_name}} </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="pull-right loader" id="select_section_loader" style="margin-top: -30px;padding-right: 21px;">
                                                    <img src="{{asset('Modules/Jitsi/Resources/assets/images/pre-loader.gif')}}" alt="" style="width: 28px;height:28px;">
                                                </div> 
                            @if ($errors->has('section'))
                            <span class="invalid-feedback invalid-select" role="alert">
                                <strong>{{ @$errors->first('section') }}</strong>
                            </span>
                            @endif
                            </div>
                        </div>
                            @if($user->role_id == 1 || $user->role_id ==5)
                            <div class="row mt-40">
                                <div class="col-lg-12" id="selectTeacherDiv">
                                    <label for="teacher_ids" class="mb-2">@lang('common.teacher') *</label>
                                            @foreach($teachers as $teacher)
                                                <div class="">
                                                    @if(isset($editdata))
                                                        <input type="radio" id="section{{@$teacher->user_id}}"
                                                            class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                            name="teacher_ids[]"
                                                            value="{{@$teacher->user_id}}" {{ $editdata->teachers->contains($teacher->user_id) ? 'checked': ''}}>
                                                        <label for="section{{@$teacher->user_id}}">{{@$teacher->full_name }}</label>
                                                    @else
                                                        <input type="radio" id="section{{@$teacher->user_id}}"
                                                            class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                            name="teacher_ids[]" value="{{@$teacher->user_id}}">
                                                        <label for="section{{@$teacher->user_id}}"> {{@$teacher->full_name}}</label>
                                                    @endif
                                                </div>


                                            @endforeach
                                        @if ($errors->has('teacher_ids'))
                                            <span class="invalid-feedback" role="alert" style="display:block">
                                                <strong>{{ $errors->first('teacher_ids') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            @endif
                            @if($user->role_id == 4 )
                            <div class="row mt-40">
                                <div class="col-lg-12" id="selectTeacherDiv">
                                    <label for="teacher_ids" class="mb-2">@lang('common.teacher') </label>
                                            @foreach($teachers as $teacher)
                                                <div class="">
                                                    @if(isset($editdata))
                                                        <input type="checkbox" id="section{{@$teacher->user_id}}"
                                                            class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                            name="teacher_ids[]"
                                                            value="{{@$teacher->user_id}}" {{ $editdata->teachers->contains($teacher->user_id) ? 'checked': ''}}>
                                                        <label for="section{{@$teacher->user_id}}">{{@$teacher->full_name }}</label>
                                                    @else
                                                        <input type="checkbox" id="section{{@$teacher->user_id}}"
                                                            class="common-checkbox form-control{{ @$errors->has('teacher_ids') ? ' is-invalid' : '' }}"
                                                            name="teacher_ids[]" value="{{@$teacher->user_id}}">
                                                        <label for="section{{@$teacher->user_id}}"> {{@$teacher->full_name}}</label>
                                                    @endif
                                                </div>


                                            @endforeach
                                            <input type="hidden" name="teacher_ids[]" value="{{$user->id}}">
                                        @if ($errors->has('teacher_ids'))
                                            <span class="invalid-feedback" role="alert" style="display:block">
                                                <strong>{{ $errors->first('teacher_ids') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            @endif
                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <input
                                                class="primary-input form-control{{ $errors->has('topic') ? ' is-invalid' : '' }}"
                                                type="text" name="topic" autocomplete="off"
                                                value="{{ isset($editdata) ?  old('topic',$editdata->topic) : old('topic') }}">
                                            <label>@lang('common.topic') <span>*</span></label>
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
                                        <label>@lang('common.date_of_meeting')<span>*</span></label>
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
                                      
                                          <label>@lang('common.meeting_time')  <span>*</span></label>
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
                                    <label>@lang('common.meeting_duration')<span>*</span></label>
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
                                    <label>@lang('common.meeting_start_before')</label>
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
                                    readonly="true"  type="text"
                                    placeholder="{{isset($editdata->attached_file) && @$editdata->attached_file != ""? getFilePath3(@$editdata->attached_file) : trans('common.attach_file')}}"
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


