@extends('backEnd.master')
@section('title')
Sports & Training Hub
@endsection

@section('mainContent')
<style>
    /* Premium Sports Hub UI Elements */
    .sports-card {
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: none;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        margin-bottom: 30px;
        overflow: hidden;
    }
    .sports-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }
    .sports-header {
        background: linear-gradient(135deg, #7c32ff 0%, #c738d8 100%);
        color: #ffffff;
        padding: 25px;
        border-bottom: none;
    }
    .sports-header h3 {
        color: #ffffff;
        font-weight: 600;
        margin: 0;
        font-size: 20px;
        letter-spacing: 0.5px;
    }
    .sports-body {
        padding: 30px;
    }
    .sports-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(124, 50, 255, 0.1);
        color: #7c32ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .sports-card:hover .sports-icon-wrapper {
        transform: scale(1.1) rotate(15deg);
        background: #7c32ff;
        color: #ffffff;
    }
    
    /* Searchable Custom Dropdown Styles */
    .custom-dropdown-container {
        position: relative;
        width: 100%;
    }
    .dropdown-trigger {
        background: #f8f9fa;
        border: 1px solid #e1e6eb;
        padding: 14px 20px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 15px;
        color: #4f5e71;
        transition: all 0.25s ease;
        font-weight: 500;
    }
    .dropdown-trigger:hover, .dropdown-trigger.active {
        border-color: #7c32ff;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(124, 50, 255, 0.15);
    }
    .dropdown-menu-list {
        position: absolute;
        top: 105%;
        left: 0;
        width: 100%;
        background: #ffffff;
        border: 1px solid #e1e6eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: none;
        max-height: 300px;
        overflow-y: auto;
        animation: slideDown 0.25s ease-out;
    }
    .dropdown-search-box {
        padding: 10px;
        border-bottom: 1px solid #e1e6eb;
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 10;
    }
    .dropdown-search-input {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e1e6eb;
        border-radius: 6px;
        outline: none;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }
    .dropdown-search-input:focus {
        border-color: #7c32ff;
    }
    .dropdown-item-opt {
        padding: 12px 20px;
        cursor: pointer;
        font-size: 14px;
        color: #4f5e71;
        transition: background 0.15s ease;
    }
    .dropdown-item-opt:hover {
        background: rgba(124, 50, 255, 0.08);
        color: #7c32ff;
        font-weight: 500;
    }
    .dropdown-item-opt.selected {
        background: #7c32ff;
        color: #fff;
        font-weight: 600;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Custom Input Transition Styles */
    .custom-input-group {
        display: none;
        margin-top: 20px;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .custom-input-group.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Timeline / Schedule Styles */
    .schedule-timeline {
        position: relative;
        padding-left: 30px;
        border-left: 2px solid rgba(124, 50, 255, 0.2);
    }
    .schedule-item {
        position: relative;
        margin-bottom: 30px;
    }
    .schedule-item:last-child {
        margin-bottom: 0;
    }
    .schedule-marker {
        position: absolute;
        left: -39px;
        top: 4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #7c32ff;
        box-shadow: 0 0 0 4px rgba(124, 50, 255, 0.15);
        transition: all 0.3s ease;
    }
    .schedule-item:hover .schedule-marker {
        background: #7c32ff;
        transform: scale(1.2);
    }
    .schedule-content {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e1e6eb;
        transition: all 0.25s ease;
    }
    .schedule-item:hover .schedule-content {
        background: #ffffff;
        border-color: #7c32ff;
        box-shadow: 0 5px 15px rgba(124, 50, 255, 0.08);
    }
    .schedule-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .schedule-meta-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 14px;
        color: #6c7a89;
    }
    .schedule-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .schedule-meta-item i {
        color: #7c32ff;
    }
    
    /* Active Countdown Styles */
    .countdown-widget {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .countdown-title {
        font-size: 15px;
        font-weight: 500;
        opacity: 0.85;
        margin-bottom: 5px;
    }
    .countdown-subtitle {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: #ffffff;
    }
    .countdown-timer-container {
        display: flex;
        gap: 12px;
    }
    .countdown-block {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        padding: 10px 15px;
        border-radius: 8px;
        text-align: center;
        min-width: 65px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .countdown-num {
        font-size: 22px;
        font-weight: 800;
        display: block;
        line-height: 1;
        margin-bottom: 2px;
        font-family: 'Courier New', monospace;
    }
    .countdown-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.75;
    }
    
    /* Generic Badges */
    .badge-premium {
        background: rgba(199, 56, 216, 0.1);
        color: #c738d8;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 15px;
    }
    .badge-active-reminder {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #2ecc71;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(46, 204, 113, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
    }
</style>

<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Sports & Practice Panel</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Student Panel</a>
                <a href="#">Sports</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Left Side: Selection form -->
            <div class="col-lg-5">
                <div class="sports-card">
                    <div class="sports-header">
                        <h3>Select Your Athletic Path</h3>
                    </div>
                    <div class="sports-body">
                        <div class="sports-icon-wrapper">
                            <i class="fas fa-trophy"></i>
                        </div>
                        
                        @if($selectedSport)
                            <span class="badge-premium">Current Selection: {{ $selectedSport->sport_name }}</span>
                        @else
                            <span class="badge-premium">No Selection Yet</span>
                        @endif

                        <form action="{{ route('student-sports-store') }}" method="POST" id="sports_selection_form">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="sport_name" class="form-label text-muted font-weight-bold mb-2">CHOOSE A SPORT</label>
                                
                                <div class="custom-dropdown-container">
                                    <div class="dropdown-trigger" id="custom_dropdown_btn">
                                        <span id="selected_sport_label">
                                            @if($selectedSport && !$selectedSport->is_custom)
                                                {{ $selectedSport->sport_name }}
                                            @elseif($selectedSport && $selectedSport->is_custom)
                                                Custom Sport
                                            @else
                                                -- Select a Sport --
                                            @endif
                                        </span>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="dropdown-menu-list" id="custom_dropdown_menu">
                                        <div class="dropdown-search-box">
                                            <input type="text" class="dropdown-search-input" placeholder="Search sports..." id="dropdown_search">
                                        </div>
                                        @foreach($predefinedSports as $sport)
                                            <div class="dropdown-item-opt" data-value="{{ $sport }}">{{ $sport }}</div>
                                        @endforeach
                                        <div class="dropdown-item-opt text-primary font-weight-bold" data-value="Custom">Custom (Manual Entry)</div>
                                    </div>
                                </div>
                                <input type="hidden" name="sport_name" id="hidden_sport_name" value="{{ $selectedSport ? ($selectedSport->is_custom ? 'Custom' : $selectedSport->sport_name) : '' }}">
                            </div>

                            <!-- Custom Sport Manual Field (Shows smoothly when Custom is selected) -->
                            <div class="form-group custom-input-group {{ ($selectedSport && $selectedSport->is_custom) ? 'active' : '' }}" id="custom_sport_field">
                                <label for="custom_sport_name" class="form-label text-muted font-weight-bold mb-2">ENTER CUSTOM SPORT NAME</label>
                                <input type="text" class="form-control bg-light" name="custom_sport_name" id="custom_sport_name" placeholder="e.g. Frisbee, Climbing..." value="{{ ($selectedSport && $selectedSport->is_custom) ? $selectedSport->sport_name : '' }}">
                            </div>

                            <button type="submit" class="primary-btn fix-gr-bg w-100 mt-4">
                                <span class="ti-check mr-2"></span> Save Sports Selection
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Schedule & Details -->
            <div class="col-lg-7">
                @if($selectedSport)
                    @if($isTeamSport)
                        <!-- Active Notification status banner -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0 font-weight-bold text-dark"><i class="fas fa-calendar-alt mr-2 text-primary"></i> Team Schedules for {{ $selectedSport->sport_name }}</h4>
                            <span class="badge-active-reminder">
                                <span class="pulse-dot"></span> In-app Alerts Configured
                            </span>
                        </div>

                        <!-- 1. Live Countdown for Next Training Session -->
                        @php
                            $nextSession = $schedules->filter(function($s) {
                                return \Carbon\Carbon::parse($s->session_date)->isFuture() || \Carbon\Carbon::parse($s->session_date)->isToday();
                            })->first();
                        @endphp

                        @if($nextSession)
                            @php
                                $session_date = $nextSession->session_date;
                                $time_parts = explode('-', $nextSession->session_time);
                                $start_time = trim($time_parts[0]);
                                $session_datetime_str = $session_date . ' ' . $start_time;
                                $session_timestamp = \Carbon\Carbon::parse($session_datetime_str)->timestamp;
                            @endphp
                            
                            <div class="countdown-widget">
                                <div>
                                    <div class="countdown-title">NEXT TRAINING COUNTDOWN</div>
                                    <h4 class="countdown-subtitle">{{ $nextSession->title }}</h4>
                                </div>
                                <div class="countdown-timer-container" id="countdown_timer" data-time="{{ $session_timestamp }}">
                                    <div class="countdown-block">
                                        <span class="countdown-num" id="cd_days">00</span>
                                        <span class="countdown-label">Days</span>
                                    </div>
                                    <div class="countdown-block">
                                        <span class="countdown-num" id="cd_hours">00</span>
                                        <span class="countdown-label">Hrs</span>
                                    </div>
                                    <div class="countdown-block">
                                        <span class="countdown-num" id="cd_mins">00</span>
                                        <span class="countdown-label">Mins</span>
                                    </div>
                                    <div class="countdown-block">
                                        <span class="countdown-num" id="cd_secs">00</span>
                                        <span class="countdown-label">Secs</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- 2. Training Schedule Timeline -->
                        <div class="sports-card">
                            <div class="sports-body">
                                @if($schedules->count() > 0)
                                    <div class="schedule-timeline">
                                        @foreach($schedules as $session)
                                            <div class="schedule-item">
                                                <div class="schedule-marker"></div>
                                                <div class="schedule-content">
                                                    <div class="schedule-title">{{ $session->title }}</div>
                                                    <ul class="schedule-meta-list">
                                                        <li class="schedule-meta-item">
                                                            <i class="far fa-calendar-alt"></i>
                                                            <span>{{ \Carbon\Carbon::parse($session->session_date)->format('l, F j, Y') }}</span>
                                                        </li>
                                                        <li class="schedule-meta-item">
                                                            <i class="far fa-clock"></i>
                                                            <span>{{ $session->session_time }}</span>
                                                        </li>
                                                        <li class="schedule-meta-item">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <span>{{ $session->venue }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="mb-3 text-muted" style="font-size: 40px;"><i class="far fa-calendar-times"></i></div>
                                        <h5>No Training Sessions Scheduled</h5>
                                        <p class="text-muted">There are currently no active team schedules configured for this sport. Check back later.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Individual Sport Panel -->
                        <div class="sports-card text-center py-5">
                            <div class="sports-body">
                                <div class="sports-icon-wrapper mx-auto" style="width: 80px; height: 80px; font-size: 32px; background: rgba(199, 56, 216, 0.1); color: #c738d8;">
                                    <i class="fas fa-user-run"></i>
                                </div>
                                <h4 class="font-weight-bold text-dark mt-4">Personal Practice Mode Active</h4>
                                <p class="text-muted px-4 mt-2">
                                    You selected <strong>{{ $selectedSport->sport_name }}</strong>, which is classified as an individual athletic path.
                                    Practice matches, endurance regimes, and performance trackers are custom-coordinated with your assigned physical education instructor.
                                </p>
                                <div class="bg-light p-4 rounded mt-4 border text-left mx-auto" style="max-width: 480px;">
                                    <div class="font-weight-bold text-dark mb-2"><i class="fas fa-info-circle text-primary mr-2"></i> Key Guidelines:</div>
                                    <ul class="pl-4 text-muted" style="list-style-type: square; line-height: 1.6;">
                                        <li>Schedule dedicated workouts with the department head.</li>
                                        <li>Log your weekly progress inside your physical education records.</li>
                                        <li>Consult with athletic trainers to draft custom training programs.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State / Onboarding -->
                    <div class="sports-card text-center py-5">
                        <div class="sports-body">
                            <div class="sports-icon-wrapper mx-auto" style="width: 90px; height: 90px; font-size: 36px; background: rgba(124, 50, 255, 0.08);">
                                <i class="fas fa-volleyball-ball"></i>
                            </div>
                            <h4 class="font-weight-bold text-dark mt-4">Unlock Your Athletic Schedule</h4>
                            <p class="text-muted px-5 mt-2">
                                Choose a sport from the options on the left to configure your schedule, sync with live practice sessions, and enable automated countdowns and training notifications.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Custom JS for Premium Interactions & Searchable Dropdown -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropdownBtn = document.getElementById("custom_dropdown_btn");
        const dropdownMenu = document.getElementById("custom_dropdown_menu");
        const dropdownSearch = document.getElementById("dropdown_search");
        const hiddenSportName = document.getElementById("hidden_sport_name");
        const selectedSportLabel = document.getElementById("selected_sport_label");
        const customSportField = document.getElementById("custom_sport_field");
        const customSportNameInput = document.getElementById("custom_sport_name");
        const dropdownItems = document.querySelectorAll(".dropdown-item-opt");

        // Toggle drop down menu display
        dropdownBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            dropdownBtn.classList.toggle("active");
            if (dropdownMenu.style.display === "block") {
                dropdownMenu.style.display = "none";
            } else {
                dropdownMenu.style.display = "block";
                dropdownSearch.focus();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function() {
            dropdownMenu.style.display = "none";
            dropdownBtn.classList.remove("active");
        });

        // Prevent dropdown menu click from propagation
        dropdownMenu.addEventListener("click", function(e) {
            e.stopPropagation();
        });

        // Search/Filter dropdown items
        dropdownSearch.addEventListener("input", function() {
            const query = dropdownSearch.value.toLowerCase().trim();
            dropdownItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        });

        // Item selection handler
        dropdownItems.forEach(item => {
            item.addEventListener("click", function() {
                const val = item.getAttribute("data-value");
                hiddenSportName.value = val;
                selectedSportLabel.textContent = val;
                
                // Remove previous selected class and add to current
                dropdownItems.forEach(opt => opt.classList.remove("selected"));
                item.classList.add("selected");
                
                // Hide dropdown
                dropdownMenu.style.display = "none";
                dropdownBtn.classList.remove("active");

                // Toggle custom sport manual entry field with smooth transition
                if (val === "Custom") {
                    customSportField.classList.add("active");
                    customSportNameInput.setAttribute("required", "required");
                    customSportNameInput.focus();
                } else {
                    customSportField.classList.remove("active");
                    customSportNameInput.removeAttribute("required");
                }
            });
        });

        // 3. Live Countdown Timer implementation
        const timerContainer = document.getElementById("countdown_timer");
        if (timerContainer) {
            const targetTimestamp = parseInt(timerContainer.getAttribute("data-time")) * 1000;
            
            function updateTimer() {
                const now = new Date().getTime();
                const difference = targetTimestamp - now;
                
                if (difference <= 0) {
                    timerContainer.innerHTML = "<div class='text-white font-weight-bold py-2'><span class='pulse-dot mr-2'></span> Session is in Progress!</div>";
                    clearInterval(timerInterval);
                    return;
                }
                
                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);
                
                document.getElementById("cd_days").textContent = String(days).padStart(2, '0');
                document.getElementById("cd_hours").textContent = String(hours).padStart(2, '0');
                document.getElementById("cd_mins").textContent = String(minutes).padStart(2, '0');
                document.getElementById("cd_secs").textContent = String(seconds).padStart(2, '0');
            }
            
            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        }
    });
</script>
@endsection
