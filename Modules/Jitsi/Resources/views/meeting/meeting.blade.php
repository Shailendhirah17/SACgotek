@extends('backEnd.master')
@section('title') 
    @lang('jitsi::jitsi.virtual_meeting')
@endsection
@section('css')
    <style>
        .propertiesname {
            text-transform: uppercase;
        }
        .recurrence-section-hide {
            display: none ;
        }
    </style>
@endsection

@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('jitsi::jitsi.virtual_meeting')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">  @lang('common.dashboard')</a>
                     <a href="#">@lang('jitsi::jitsi.jitsi')</a>
                    <a href="#">@lang('jitsi::jitsi.virtual_meeting')</a>
                </div>
            </div>
        </div>
    </section>


    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                @include('jitsi::meeting.includes.form')
                @include('jitsi::meeting.includes.list')
            </div>
        </div>
    </section>
@endsection

@push('script')
    @if(isset($editdata))
        @if ( old('is_recurring',$editdata->is_recurring) == 1)
            <script>$(".recurrence-section-hide").show();</script>
        @else
            <script>$(".recurrence-section-hide").hide();</script>
        @endif
    @elseif( old('is_recurring') == 1)
        <script>$(".recurrence-section-hide").show();</script>
    @else
        <script>$(".recurrence-section-hide").hide();</script>
    @endif
    @if(isset($editdata))
        <script>$(".default-settings").show();</script>
    @else
        <script>$(".default-settings").hide();</script>
    @endif
    <script>
        $(document).ready(function(){
            $(document).on('change','.user_type',function(){
                let userType = $(this).val();
                $("#selectSectionss").select2().empty()
                $.get('{{ route('jitsi.user.list.user.type.wise') }}',{ user_type: userType },function(res){
                    $("#selectSectionss").select2().empty()
                    $.each(res.users, function( index, item ) {
                        $('#selectSectionss').append(new Option(item.full_name, item.id))
                    });
                })
            })

            $(document).on('click','.recurring-type',function(){

                if($("input[name='is_recurring']:checked").val() == 0){
                    $(".recurrence-section-hide").hide();
                }else{
                    $(".recurrence-section-hide").show();
                }
            })

            $(document).on('click','.chnage-default-settings',function(){
                if($(this).val() == 0){
                    $(".default-settings").hide();
                }else{
                    $(".default-settings").show();
                }
            })
        })
    </script>
@endpush
