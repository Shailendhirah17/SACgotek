@php
    $school_config = schoolConfig();
    $isSchoolAdmin = Session::get('isSchoolAdmin');
@endphp
<!-- sidebar part here -->
<nav id="sidebar" class="sidebar">

    <div class="sidebar-header update_sidebar">
        @if (Auth::user()->role_id != 2 && Auth::user()->role_id != 3)
            @if (userPermission('dashboard'))
                @if (moduleStatusCheck('Saas') == true &&
                    Auth::user()->is_administrator == 'yes' &&
                    Session::get('isSchoolAdmin') == false &&
                    Auth::user()->role_id == 1)
                    <a href="{{ route('superadmin-dashboard') }}" id="superadmin-dashboard">
                @elseif (moduleStatusCheck('Saas') == true &&
                    moduleStatusCheck('SaasHr') == true &&
                    Auth::user()->is_administrator == 'yes' &&
                    Session::get('isSchoolAdmin') == false)
                    <a href="{{ route('superadmin-dashboard') }}" id="superadmin-dashboard">
                @else
                    <a href="{{ route('admin-dashboard') }}" id="admin-dashboard">
                @endif
            @else
                <a href="{{url('/')}}" id="admin-dashboard">
            @endif
        @else
            <a href="{{ url('/') }}" id="admin-dashboard">
        @endif
        @if (!is_null($school_config->logo))
            <div class="sac-sidebar-brand" style="display: flex; align-items: center; gap: 10px; padding: 5px 0;">
                <svg class="sac-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width: 42px; height: 42px; filter: drop-shadow(0 0 6px rgba(0, 242, 254, 0.6));">
                    <defs>
                        <linearGradient id="sacGradSidebar" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#4facfe" />
                            <stop offset="100%" stop-color="#00f2fe" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="44" fill="none" stroke="url(#sacGradSidebar)" stroke-width="2.5" stroke-dasharray="6 3" stroke-opacity="0.7" />
                    <polygon points="50,16 84,26 50,36 16,26" fill="url(#sacGradSidebar)" fill-opacity="0.2" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linejoin="round" />
                    <path d="M30,30 L30,44 C30,50 70,50 70,44 L70,30" fill="none" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linecap="round" />
                    <path d="M50,26 L22,32 L20,46 C20,49 18,49 18,46 L18,34" fill="none" stroke="url(#sacGradSidebar)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M50,76 C35,72 20,76 20,76 L20,54 C20,54 35,50 50,54 Z" fill="url(#sacGradSidebar)" fill-opacity="0.15" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linejoin="round" />
                    <path d="M50,76 C65,72 80,76 80,76 L80,54 C80,54 65,50 50,54 Z" fill="url(#sacGradSidebar)" fill-opacity="0.15" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linejoin="round" />
                    <line x1="50" y1="54" x2="50" y2="76" stroke="url(#sacGradSidebar)" stroke-width="2.5" stroke-linecap="round" />
                </svg>
                <span class="brand-title" style="font-size: 22px; font-weight: 800; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-family: 'Poppins', sans-serif; letter-spacing: 1px;">SAC</span>
            </div>
        @else
            <div class="sac-sidebar-brand" style="display: flex; align-items: center; gap: 10px; padding: 5px 0;">
                <svg class="sac-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width: 42px; height: 42px; filter: drop-shadow(0 0 6px rgba(0, 242, 254, 0.6));">
                    <defs>
                        <linearGradient id="sacGradSidebar" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#4facfe" />
                            <stop offset="100%" stop-color="#00f2fe" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="44" fill="none" stroke="url(#sacGradSidebar)" stroke-width="2.5" stroke-dasharray="6 3" stroke-opacity="0.7" />
                    <polygon points="50,16 84,26 50,36 16,26" fill="url(#sacGradSidebar)" fill-opacity="0.2" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linejoin="round" />
                    <path d="M30,30 L30,44 C30,50 70,50 70,44 L70,30" fill="none" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linecap="round" />
                    <path d="M50,26 L22,32 L20,46 C20,49 18,49 18,46 L18,34" fill="none" stroke="url(#sacGradSidebar)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M50,76 C35,72 20,76 20,76 L20,54 C20,54 35,50 50,54 Z" fill="url(#sacGradSidebar)" fill-opacity="0.15" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linejoin="round" />
                    <path d="M50,76 C65,72 80,76 80,76 L80,54 C80,54 65,50 50,54 Z" fill="url(#sacGradSidebar)" fill-opacity="0.15" stroke="url(#sacGradSidebar)" stroke-width="3" stroke-linejoin="round" />
                    <line x1="50" y1="54" x2="50" y2="76" stroke="url(#sacGradSidebar)" stroke-width="2.5" stroke-linecap="round" />
                </svg>
                <span class="brand-title" style="font-size: 22px; font-weight: 800; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-family: 'Poppins', sans-serif; letter-spacing: 1px;">SAC</span>
            </div>
        @endif
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>

    </div>
    @if (Auth::user()->is_saas == 0)
       
        <ul class="sidebar_menu list-unstyled" id="sidebar_menu">
            @includeIf('backEnd.menu.org_chart')
            @if (moduleStatusCheck('Saas') == true &&
                Auth::user()->is_administrator == 'yes' &&
                Session::get('isSchoolAdmin') == false &&
                Auth::user()->role_id == 1)
                @include('saas::menu.Saas')

            @elseif(moduleStatusCheck('Saas') == true &&
                Auth::user()->is_administrator == 'yes' &&
                Session::get('isSchoolAdmin') == false &&
                moduleStatusCheck('SaasHr') == true)
                @include('saas::menu.Saas')
            @else
                @if(auth()->user()->role_id == 2)
                    @includeIf('backEnd.menu.student', ['paid_modules' => $paid_modules])
                @elseif(auth()->user()->role_id == 3)
                    @includeIf('backEnd.menu.parent', ['children' => $children, 'paid_modules' => $paid_modules])
                @else
                    
                    @includeIf('backEnd.menu.staff', ['paid_modules' => $paid_modules])
                @endif
            @endif
        </ul>
    @endif
</nav>
<!-- sidebar part end -->
@push('script')
    <script>
        $(document).ready(function(){
            var sections=[];
            $('.menu_seperator').each(function() { sections.push($(this).data('section')); });

            jQuery.each(sections, function(index, section) {
                if($('.'+section).length == 0) {
                    $('#seperator_'+section).addClass('d-none');
                }else{
                    $('#seperator_'+section).removeClass('d-none');
                }
            });
        })

    </script>
@endpush
