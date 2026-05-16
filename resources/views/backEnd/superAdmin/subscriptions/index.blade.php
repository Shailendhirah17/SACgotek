@extends('backEnd.superAdmin.layouts.master')
@section('title', 'SaaS Subscriptions')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">SaaS Subscription Control Center</span>
        <div class="usa-card-tools">
            <form action="{{ route('superadmin.subscriptions.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" class="usa-form-control usa-form-control-sm" placeholder="Search schools..." value="{{ request('search') }}">
                <button type="submit" class="usa-btn usa-btn-primary usa-btn-sm">Search</button>
            </form>
        </div>
    </div>

    <div class="usa-table-responsive">
        <table class="usa-table">
            <thead>
                <tr>
                    <th>School (Tenant)</th>
                    <th>Logins</th>
                    <th>Outstanding Balance</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Quick Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $school)
                @php
                    $expiry = $school->ending_date ? \Carbon\Carbon::parse($school->ending_date) : null;
                    $isExpired = $expiry ? $expiry->isPast() : false;
                    $isNear = $expiry ? $expiry->diffInDays(now()) <= 7 && !$isExpired : false;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $school->school_name }}</strong><br>
                        <small class="text-muted">{{ $school->email }}</small>
                    </td>
                    <td>
                        <span class="usa-badge" style="background: rgba(102,126,234,0.1);">{{ number_format($school->student_login_count) }} Logins</span>
                    </td>
                    <td>
                        <strong style="color: {{ $school->outstanding_balance > 0 ? 'var(--usa-danger)' : 'var(--usa-success)' }}">
                            ₹{{ number_format($school->outstanding_balance, 2) }}
                        </strong>
                    </td>
                    <td>
                        @if($expiry)
                            {{ $expiry->format('d M, Y') }}
                            @if($isExpired)
                                <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Expired</small>
                            @elseif($isNear)
                                <br><small class="text-warning"><i class="fas fa-clock"></i> Expiring Soon</small>
                            @endif
                        @else
                            <span class="text-muted">No Expiry (Lifetime)</span>
                        @endif
                    </td>
                    <td>
                        @if($school->active_status == 1)
                            <span class="usa-badge usa-badge-success">Active</span>
                        @else
                            <span class="usa-badge usa-badge-danger">Suspended</span>
                        @endif
                    </td>
                    <td>
                        <button class="usa-btn usa-btn-outline usa-btn-sm" onclick="openExtendModal('{{ $school->id }}', '{{ $school->school_name }}', '{{ $school->ending_date }}', '{{ $school->active_status }}', '{{ $school->plan_type }}')">
                            <i class="fas fa-edit"></i> Manage
                        </button>
                        <form action="{{ route('superadmin.subscriptions.toggle', $school->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="usa-btn {{ $school->active_status == 1 ? 'usa-btn-danger' : 'usa-btn-success' }} usa-btn-sm">
                                {{ $school->active_status == 1 ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="usa-empty-state">No tenant schools found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $subscriptions->links() }}
    </div>
</div>

<!-- Extend/Manage Modal -->
<div id="subscriptionModal" class="usa-modal-overlay" style="display:none;">
    <div class="usa-modal-content" style="max-width: 450px;">
        <div class="usa-modal-header">
            <h3 id="modalSchoolName">Manage Subscription</h3>
            <button class="usa-modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="extendForm" method="POST">
            @csrf
            @method('PUT')
            <div class="usa-modal-body">
                <div style="margin-bottom: 15px;">
                    <label class="usa-form-label">Plan Type</label>
                    <input type="text" name="plan_type" id="modalPlanType" class="usa-form-control" placeholder="Trial, Basic, Pro...">
                </div>
                <div style="margin-bottom: 15px;">
                    <label class="usa-form-label">Expiry Date</label>
                    <input type="date" name="ending_date" id="modalExpiryDate" class="usa-form-control">
                </div>
                <div style="margin-bottom: 15px;">
                    <label class="usa-form-label">Status</label>
                    <select name="active_status" id="modalStatus" class="usa-form-control">
                        <option value="1">Active</option>
                        <option value="0">Suspended</option>
                    </select>
                </div>
            </div>
            <div class="usa-modal-footer" style="padding: 20px; border-top: 1px solid var(--usa-border); text-align: right;">
                <button type="button" class="usa-btn usa-btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="usa-btn usa-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
    .usa-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 9999;
        display: flex; align-items: center; justify-content: center;
    }
    .usa-modal-content { background: var(--usa-bg-card); border-radius: 16px; border: 1px solid var(--usa-border); width: 90%; }
    .usa-modal-header { padding: 20px; border-bottom: 1px solid var(--usa-border); display: flex; justify-content: space-between; align-items: center; }
    .usa-modal-body { padding: 20px; }
    .usa-form-label { display: block; margin-bottom: 8px; font-size: 13px; color: var(--usa-text-muted); }
    .usa-modal-close { background: none; border: none; color: #fff; font-size: 24px; cursor: pointer; }
</style>

<script>
    function openExtendModal(id, name, date, status, plan) {
        document.getElementById('modalSchoolName').innerText = 'Manage: ' + name;
        document.getElementById('modalExpiryDate').value = date;
        document.getElementById('modalStatus').value = status;
        document.getElementById('modalPlanType').value = plan;
        document.getElementById('extendForm').action = '/superadmin/subscriptions/' + id;
        document.getElementById('subscriptionModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('subscriptionModal').style.display = 'none';
    }
</script>
@endsection
