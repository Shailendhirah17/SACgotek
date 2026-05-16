@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Edit School')

@section('styles')
<link href="{{asset('public/backEnd/vendors/js/select2/select2.css')}}" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        background-color: var(--usa-bg-dark);
        border: 1px solid var(--usa-border);
        border-radius: 8px;
        height: 40px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--usa-text-primary);
        font-size: 13px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }
    .select2-dropdown {
        background-color: var(--usa-bg-card);
        border: 1px solid var(--usa-border);
        color: var(--usa-text-primary);
    }
    .select2-search__field {
        background-color: var(--usa-bg-dark) !important;
        color: var(--usa-text-primary) !important;
        border: 1px solid var(--usa-border) !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: var(--usa-primary);
    }
</style>
@endsection

@section('content')
<div class="usa-card" style="max-width: 700px;">
    <div class="usa-card-header">
        <span class="usa-card-title">Edit School: {{ $school->school_name }}</span>
        <a href="{{ route('superadmin.school-list') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <form action="{{ route('superadmin.school.update', $school->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">School Name *</label>
                    <input type="text" name="school_name" class="usa-form-control" value="{{ old('school_name', $school->school_name) }}" required>
                    @error('school_name') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Email & Static Row (Admin info) -->
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Email *</label>
                    <input type="email" name="email" class="usa-form-control" value="{{ old('email', $school->email) }}" required>
                    @error('email') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Admin Actions</label>
                    <div style="padding: 10px; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid var(--usa-border); font-size: 13px; color: var(--usa-text-muted);">
                        Password managed at School level.
                    </div>
                </div>
            </div>

            <!-- Row 3: Primary and Secondary Phone -->
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Primary Contact Number</label>
                    <input type="text" name="primary_phone" class="usa-form-control" value="{{ old('primary_phone', $school->phone) }}">
                    @error('primary_phone') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Secondary Contact Number</label>
                    <input type="text" name="secondary_phone" class="usa-form-control" value="{{ old('secondary_phone', $school->secondary_phone) }}">
                    @error('secondary_phone') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-12">
                <hr style="border-color: rgba(255,255,255,0.05); margin: 20px 0;">
                <h6 style="color: var(--usa-text-muted); margin-bottom: 15px; font-weight: 500;">
                    <i class="fas fa-network-wired" style="margin-right: 8px;"></i> Routing Settings (SaaS)
                </h6>
            </div>
            
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">System Subdomain *</label>
                    <div style="display: flex; align-items: center; background: rgba(0,0,0,0.2); border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); overflow: hidden;">
                        <input type="text" name="domain" class="usa-form-control" value="{{ old('domain', $school->domain) }}" required style="border: none; border-radius: 0; background: transparent; padding-right: 5px; text-align: right;" placeholder="schoolname">
                        <span style="color: var(--usa-text-muted); padding: 0 12px; font-size: 13px; font-family: monospace;">.{{ preg_replace('#^https?://#', '', rtrim(env('APP_URL', 'localhost'), '/')) }}</span>
                    </div>
                    @error('domain') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Custom Domain</label>
                    <input type="text" name="custom_domain" class="usa-form-control" value="{{ old('custom_domain', $school->custom_domain) }}" placeholder="e.g., erp.myschool.com">
                    @error('custom_domain') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-12">
                <hr style="border-color: rgba(255,255,255,0.05); margin: 20px 0;">
                <h6 style="color: var(--usa-text-muted); margin-bottom: 15px; font-weight: 500;">
                    <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i> Geographic Location
                </h6>
            </div>

            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">State (for Map) *</label>
                    <select name="state_id" id="state_id" class="usa-form-control select2" required>
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $school->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">District (City) *</label>
                    <select name="city_id" id="city_id" class="usa-form-control select2" required>
                        <option value="">Select District</option>
                        @if($school->city_id)
                            @php 
                                $currentCity = \Illuminate\Support\Facades\DB::table('sm_cities')->where('id', $school->city_id)->first();
                            @endphp
                            @if($currentCity)
                                <option value="{{ $currentCity->id }}" selected>{{ $currentCity->name }}</option>
                            @endif
                        @endif
                    </select>
                </div>
            </div>


            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Address</label>
                    <textarea name="address" class="usa-form-control" rows="3">{{ old('address', $school->address) }}</textarea>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 12px; margin-top: 12px;">
            <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Update School</button>
            <a href="{{ route('superadmin.school-list') }}" class="usa-btn usa-btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Geographic Location Logic (Vanilla JS)
    (function() {
        // Safe Select2 Refresh helper
        function refreshSelect2Silently(id) {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#' + id).select2({ width: '100%', placeholder: "Search and select..." });
            }
        }

        // The core logic
        function handleStateChange(stateId) {
            const citySelect = document.getElementById('city_id');
            if (!citySelect) return;

            if (stateId) {
                // Show loading state
                citySelect.innerHTML = '<option value="">Loading districts...</option>';
                refreshSelect2Silently('city_id');

                // Native Fetch call
                fetch('/geo/ajax-get-cities?state_id=' + stateId)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        // Clear and populate
                        citySelect.innerHTML = '<option value="">Select District</option>';
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });

                        // Final Sync with UI
                        refreshSelect2Silently('city_id');
                    })
                    .catch(error => {
                        console.error("Geographic Error:", error);
                        citySelect.innerHTML = '<option value="">Error loading</option>';
                        refreshSelect2Silently('city_id');
                    });
            } else {
                citySelect.innerHTML = '<option value="">Select District</option>';
                refreshSelect2Silently('city_id');
            }
        }

        // Attach listener using delegation
        document.addEventListener('change', function(e) {
            if (e.target && e.target.id === 'state_id') {
                handleStateChange(e.target.value);
            }
        });
    })();
</script>
@endsection
