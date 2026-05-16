
@if(userPermission(816) && menuStatus(816))
    <li data-position="{{menuPosition(816)}}" class="sortable_li">
        <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
            <div class="nav_icon_small">
                <span class="flaticon-reading"></span>
            </div>
            <div class="nav_title">
               <span> @lang('jitsi::jitsi.jitsi')</span>
                @if (config('app.app_sync'))
                    <span class="demo_addons">Addon</span>
                @endif
            </div>
        </a>
        <ul class="list-unstyled">
            @if(userPermission(817)  && menuStatus(817))
                <li data-position="{{menuPosition(817)}}">
                    <a href="{{ route('jitsi.virtual-class')}}">@lang('jitsi::jitsi.virtual_class')</a>
                </li>
            @endif
            @if(userPermission(822) && menuStatus(822))
                    <li data-position="{{menuPosition(822)}}">
                    <a href="{{ route('jitsi.meetings') }}">@lang('jitsi::jitsi.virtual_meeting')</a>
                </li>
            @endif
            @if(userPermission(827) && menuStatus(827))
                    <li data-position="{{menuPosition(827)}}">
                    <a href="{{route('jitsi.virtual.class.reports.show')}}">@lang('jitsi::jitsi.class_reports')</a>
                </li>
            @endif

            @if(userPermission(829) && menuStatus(829))
                <li data-position="{{menuPosition(829)}}">
                    <a href="{{route('jitsi.meeting.reports.show')}}">@lang('jitsi::jitsi.meeting_reports')</a>
                </li>
            @endif
            @if(userPermission(831) && menuStatus(831))
                    <li data-position="{{menuPosition(831)}}">
                    <a href="{{ route('jitsi.settings') }}">@lang('jitsi::jitsi.settings')</a>
                </li>
            @endif
        </ul>
    </li>
    <!-- jitsi Menu  -->
@endif
