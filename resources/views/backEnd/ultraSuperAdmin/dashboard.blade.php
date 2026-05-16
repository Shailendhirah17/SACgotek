@extends('backEnd.ultraSuperAdmin.layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="row" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 28px;">
    <!-- Stat Cards -->
    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(120,50,255,0.12); color: var(--usa-primary-light);">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="usa-stat-value">{{ $totalSchoolGroups }}</div>
            <div class="usa-stat-label">School Groups</div>
        </div>
    </div>

    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(255,136,0,0.12); color: var(--usa-secondary);">
                <i class="fas fa-school"></i>
            </div>
            <div class="usa-stat-value">{{ $totalSchools }}</div>
            <div class="usa-stat-label">Total Schools</div>
        </div>
    </div>

    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(52,211,153,0.12); color: var(--usa-success);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="usa-stat-value">{{ number_format($totalStudents) }}</div>
            <div class="usa-stat-label">Total Students</div>
        </div>
    </div>

    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(129,140,248,0.12); color: var(--usa-info);">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="usa-stat-value">{{ $totalSuperAdmins }}</div>
            <div class="usa-stat-label">Super Admins</div>
        </div>
    </div>
</div>

<div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 28px;">
    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(251,191,36,0.12); color: var(--usa-warning);">
                <i class="fas fa-gem"></i>
            </div>
            <div class="usa-stat-value">{{ $activeSubscriptions }}</div>
            <div class="usa-stat-label">Active Subscriptions</div>
        </div>
    </div>

    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(248,113,113,0.12); color: var(--usa-danger);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="usa-stat-value">{{ $expiringSubscriptions }}</div>
            <div class="usa-stat-label">Expiring (&lt;30 days)</div>
        </div>
    </div>

    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(52,211,153,0.12); color: var(--usa-success);">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="usa-stat-value">{{ number_format($totalStaff) }}</div>
            <div class="usa-stat-label">Total Staff</div>
        </div>
    </div>

    <div style="flex: 1; min-width: 200px;">
        <div class="usa-stat-card">
            <div class="usa-stat-icon" style="background: rgba(120,50,255,0.12); color: var(--usa-primary-light);">
                <i class="fas fa-users"></i>
            </div>
            <div class="usa-stat-value">{{ number_format($totalUsers) }}</div>
            <div class="usa-stat-label">Total Platform Users</div>
        </div>
    </div>
</div>

<!-- Main content area -->
<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Recent School Groups -->
    <div style="flex: 1.5; min-width: 400px;">
        <div class="usa-card">
            <div class="usa-card-header">
                <div class="usa-card-title">
                    <i class="fas fa-layer-group" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
                    Recent School Groups
                </div>
                <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-btn usa-btn-outline usa-btn-sm">View All</a>
            </div>

            @if(isset($recentGroups) && $recentGroups->count() > 0)
            <table class="usa-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Schools</th>
                        <th>Plan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentGroups as $group)
                    <tr>
                        <td style="color: var(--usa-text-primary); font-weight: 600;">{{ $group->name }}</td>
                        <td><span class="usa-badge usa-badge-primary">{{ $group->code }}</span></td>
                        <td>{{ $group->schools_count }}</td>
                        <td>{{ ucfirst($group->subscription_plan) }}</td>
                        <td>
                            <span class="usa-badge {{ $group->active_status ? 'usa-badge-success' : 'usa-badge-danger' }}">
                                {{ $group->active_status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align: center; padding: 40px; color: var(--usa-text-muted);">
                <i class="fas fa-layer-group" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                <p>No school groups created yet</p>
                <a href="{{ route('ultrasuperadmin.school-groups.create') }}" class="usa-btn usa-btn-primary" style="margin-top: 12px;">
                    <i class="fas fa-plus"></i> Create First Group
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions & System Health -->
    <div style="flex: 1; min-width: 300px;">
        <!-- Quick Actions -->
        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-title" style="margin-bottom: 16px;">
                <i class="fas fa-bolt" style="color: var(--usa-secondary); margin-right: 8px;"></i>
                Quick Actions
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('ultrasuperadmin.school-groups.create') }}" class="usa-btn usa-btn-primary" style="justify-content: center;">
                    <i class="fas fa-plus"></i> Create School Group
                </a>
                <a href="{{ route('ultrasuperadmin.super-admins.create') }}" class="usa-btn usa-btn-outline" style="justify-content: center;">
                    <i class="fas fa-user-plus"></i> Add Super Admin
                </a>
                <a href="{{ route('ultrasuperadmin.features.index') }}" class="usa-btn usa-btn-outline" style="justify-content: center;">
                    <i class="fas fa-toggle-on"></i> Manage Features
                </a>
                <a href="{{ route('ultrasuperadmin.subscriptions.index') }}" class="usa-btn usa-btn-outline" style="justify-content: center;">
                    <i class="fas fa-gem"></i> Manage Subscriptions
                </a>
            </div>
        </div>

        <!-- System Health -->
        @if(isset($systemHealth) && count($systemHealth) > 0)
        <div class="usa-card">
            <div class="usa-card-title" style="margin-bottom: 16px;">
                <i class="fas fa-heartbeat" style="color: var(--usa-success); margin-right: 8px;"></i>
                System Health
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($systemHealth as $key => $value)
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                    <span style="color: var(--usa-text-muted); text-transform: capitalize;">{{ str_replace('_', ' ', $key) }}</span>
                    <span style="color: var(--usa-text-primary); font-weight: 600;">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Advanced Financial Aggregation -->
<div class="usa-card" style="margin-top: 20px;">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-money-bill-wave" style="color: var(--usa-success); margin-right: 8px;"></i>
        Platform Financial Overview
    </div>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <!-- Subscription Revenue -->
        <div style="flex: 1; min-width: 200px; padding: 20px; background: rgba(52,211,153,0.05); border-radius: 12px; border: 1px solid rgba(52,211,153,0.2);">
            <div style="color: var(--usa-text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Subscription Revenue</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--usa-success);">₹{{ number_format($financeData['total_subscription_revenue'] ?? 0, 2) }}</div>
        </div>

        <!-- School-level Volume -->
        <div style="flex: 1; min-width: 200px; padding: 20px; background: rgba(120,50,255,0.05); border-radius: 12px; border: 1px solid rgba(120,50,255,0.2);">
            <div style="color: var(--usa-text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total Platform Volume (Fees)</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--usa-primary-light);">₹{{ number_format($financeData['total_school_revenue'] ?? 0, 2) }}</div>
        </div>

        <!-- Platform Fees (estimated) -->
        <div style="flex: 1; min-width: 200px; padding: 20px; background: rgba(251,191,36,0.05); border-radius: 12px; border: 1px solid rgba(251,191,36,0.2);">
            <div style="color: var(--usa-text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Platform Transaction Fees (2%)</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--usa-warning);">₹{{ number_format($financeData['platform_fee_revenue'] ?? 0, 2) }}</div>
        </div>

        <!-- Net Platform Revenue -->
        <div style="flex: 1; min-width: 200px; padding: 20px; background: rgba(248,113,113,0.05); border-radius: 12px; border: 1px solid rgba(248,113,113,0.2);">
            <div style="color: var(--usa-text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Net Platform Revenue</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--usa-danger);">₹{{ number_format($financeData['net_platform_revenue'] ?? 0, 2) }}</div>
        </div>
    </div>
</div>

<!-- Subscription Plan Distribution -->
@if(isset($planDistribution) && $planDistribution->count() > 0)
<div class="usa-card" style="margin-top: 20px;">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-chart-pie" style="color: var(--usa-secondary); margin-right: 8px;"></i>
        Subscription Plan Distribution
    </div>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        @foreach($planDistribution as $plan)
        <div style="flex: 1; min-width: 150px; text-align: center; padding: 20px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--usa-border);">
            <div style="font-size: 32px; font-weight: 800; color: var(--usa-primary-light);">{{ $plan->total }}</div>
            <div style="font-size: 13px; color: var(--usa-text-secondary); text-transform: capitalize; margin-top: 4px;">{{ $plan->subscription_plan }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Geographic Intelligence Map -->
<div class="usa-card" style="margin-top: 20px;">
    <div class="usa-card-title" style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <i class="fas fa-globe-asia" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
            Geographic Intelligence
        </div>
        <div style="font-size: 12px; font-weight: normal; color: var(--usa-text-muted);">
            {{ count($geoData['map_points']) }} Active Schools Mapped
        </div>
    </div>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <!-- The Map -->
        <div style="flex: 2; min-width: 400px; height: 400px; border-radius: 12px; overflow: hidden; border: 1px solid var(--usa-border);">
            <div id="geoMap" style="width: 100%; height: 100%;"></div>
        </div>
        
        <!-- Regional Data -->
        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 16px;">
            <!-- Top States -->
            <div style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 12px; border: 1px solid var(--usa-border); flex: 1;">
                <h6 style="color: var(--usa-text-primary); margin-top: 0; margin-bottom: 12px; font-size: 14px; font-weight: 600;">Coverage by State</h6>
                @if(count($geoData['state_distribution']) > 0)
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach(array_slice($geoData['state_distribution'], 0, 5) as $state)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--usa-text-secondary); font-size: 13px;">{{ $state->state_name }}</span>
                                <span class="usa-badge usa-badge-primary" style="font-size: 11px;">{{ $state->total_schools }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <span style="color: var(--usa-text-muted); font-size: 12px;">No state data available</span>
                @endif
            </div>

            <!-- Opportunities -->
            <div style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 12px; border: 1px solid var(--usa-border); flex: 1;">
                <h6 style="color: var(--usa-text-primary); margin-top: 0; margin-bottom: 12px; font-size: 14px; font-weight: 600;">Growth Opportunities</h6>
                @if(count($geoData['growth_opportunities']) > 0)
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach(array_slice($geoData['growth_opportunities'], 0, 5) as $opp)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--usa-text-secondary); font-size: 13px;">{{ $opp->name }}</span>
                                <span class="usa-badge usa-badge-warning" style="font-size: 11px; background: rgba(251,191,36,0.1); color: var(--usa-warning);">{{ $opp->school_count }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <span style="color: var(--usa-text-muted); font-size: 12px;">No regions identified yet</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map focused roughly on India if data is empty, otherwise auto-fit
        var map = L.map('geoMap').setView([20.5937, 78.9629], 4);
        
        // CartoDB Dark Matter tile layer for premium SaaS aesthetic
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        var points = {!! json_encode($geoData['map_points']) !!};
        
        var bounds = [];
        
        points.forEach(function(p) {
            if (p.latitude && p.longitude) {
                var lat = parseFloat(p.latitude);
                var lng = parseFloat(p.longitude);
                if(!isNaN(lat) && !isNaN(lng)) {
                    var marker = L.circleMarker([lat, lng], {
                        radius: 6,
                        fillColor: '#7832ff',
                        color: '#fff',
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(map);
                    
                    marker.bindPopup("<b>" + p.school_name + "</b><br>Group ID: " + p.school_group_id);
                    bounds.push([lat, lng]);
                }
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, {padding: [50, 50]});
        }
    });
</script>
@endsection
