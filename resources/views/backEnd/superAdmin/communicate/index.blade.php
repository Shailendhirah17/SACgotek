@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Communicate')

@section('content')
<div class="row">
    <!-- Send Email Section -->
    <div class="col-lg-6">
        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title"><i class="fas fa-envelope"></i> Send Email to Schools</span>
            </div>
            <form action="{{ route('superadmin.communicate.send-email') }}" method="POST">
                @csrf
                <div class="usa-form-group">
                    <label class="usa-form-label">Recipients Scope</label>
                    <select name="recipients" class="usa-form-control" id="recipientType" onchange="toggleInputs()">
                        <option value="all">All Schools</option>
                        @if($schoolGroups->isNotEmpty())
                        <option value="organization">By Organization</option>
                        @endif
                        <option value="selected">Selected Schools</option>
                    </select>
                </div>

                <div id="organizationSelect" class="usa-form-group" style="display: none;">
                    <label class="usa-form-label">Select Organizations</label>
                    <select name="organization_ids[]" class="usa-form-control" multiple style="height: 100px;">
                        @foreach($schoolGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->activeSchoolsCount() }} schools)</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                </div>

                <div id="schoolSelect" class="usa-form-group" style="display: none;">
                    <label class="usa-form-label">Select Schools</label>
                    <select name="school_ids[]" class="usa-form-control" multiple style="height: 120px;">
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->school_name }} ({{ $school->email ?: 'no email' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="usa-form-group">
                    <label class="usa-form-label">Subject</label>
                    <input type="text" name="subject" class="usa-form-control" required>
                </div>

                <div class="usa-form-group">
                    <label class="usa-form-label">Message</label>
                    <textarea name="message" class="usa-form-control" rows="6" required></textarea>
                </div>

                <button type="submit" class="usa-btn usa-btn-primary w-100"><i class="fas fa-paper-plane"></i> Send Email</button>
            </form>
        </div>
    </div>

    <!-- Platform Notice Section -->
    <div class="col-lg-6">
        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title"><i class="fas fa-bullhorn"></i> Platform Notice</span>
            </div>
            <form action="{{ route('superadmin.communicate.send-notice') }}" method="POST">
                @csrf
                <div class="usa-form-group">
                    <label class="usa-form-label">Notice Type</label>
                    <select name="type" class="usa-form-control">
                        <option value="info">Information</option>
                        <option value="warning">Warning</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Title</label>
                    <input type="text" name="title" class="usa-form-control" required>
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Details</label>
                    <textarea name="message" class="usa-form-control" rows="6" required></textarea>
                </div>
                <button type="submit" class="usa-btn usa-btn-primary w-100" style="background: var(--usa-secondary);"><i class="fas fa-broadcast-tower"></i> Publish Notice</button>
            </form>
        </div>
    </div>
</div>

<!-- History Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title"><i class="fas fa-history"></i> Communication History</span>
            </div>
            <div class="table-responsive">
                <table class="usa-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Admin</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sentMessages as $msg)
                            <tr>
                                <td><span class="usa-badge {{ $msg->entity_type == 'Email' ? 'usa-badge-info' : 'usa-badge-warning' }}">{{ $msg->entity_type }}</span></td>
                                <td>{{ $msg->description }}</td>
                                <td>{{ $msg->superAdmin ? $msg->superAdmin->full_name : 'System' }}</td>
                                <td>{{ $msg->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center p-4">No history records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleInputs() {
        const type = document.getElementById('recipientType').value;
        const orgDiv = document.getElementById('organizationSelect');
        const schDiv = document.getElementById('schoolSelect');
        
        orgDiv.style.display = (type === 'organization') ? 'block' : 'none';
        schDiv.style.display = (type === 'selected') ? 'block' : 'none';
    }
</script>
@endsection
