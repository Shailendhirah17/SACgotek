@extends('backEnd.master')
@section('title') Thirukkural @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Thirukkural</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Library &amp; Book Bank</a>
                <a href="#">Thirukkural</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Kural Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-book-open mr-2" style="color:#8b5e3c;"></i> Add Thirukkural</h4>
                    <form action="{{ route('thirukkural.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Kural No.</label>
                            <input type="number" name="kural_no" class="form-control" placeholder="1 – 1330">
                        </div>
                        <div class="form-group">
                            <label>Section (Paal)</label>
                            <select name="section" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($sections as $section)
                                    <option>{{ $section }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Chapter (Adhikaram)</label>
                            <input type="text" name="chapter" class="form-control" placeholder="Chapter name">
                        </div>
                        <div class="form-group">
                            <label>Kural (Tamil)</label>
                            <textarea name="kural_tamil" class="form-control" rows="3" placeholder="Tamil text..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Kural (English)</label>
                            <textarea name="kural_english" class="form-control" rows="3" placeholder="English translation..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Explanation</label>
                            <textarea name="explanation" class="form-control" rows="3" placeholder="Meaning / explanation..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-save mr-1"></i> Save Kural
                        </button>
                    </form>
                </div>
            </div>

            {{-- Kural List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2" style="color:#8b5e3c;"></i> Thirukkural List</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="kuralTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Kural No.</th>
                                    <th>Section</th>
                                    <th>Chapter</th>
                                    <th>Tamil</th>
                                    <th>English</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kurals as $i => $kural)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><span class="badge badge-warning">{{ $kural->kural_no }}</span></td>
                                    <td>{{ $kural->section ?? '—' }}</td>
                                    <td>{{ $kural->chapter ?? '—' }}</td>
                                    <td style="font-family: serif;">{{ Str::limit($kural->kural_tamil, 40) }}</td>
                                    <td>{{ Str::limit($kural->kural_english, 40) }}</td>
                                    <td>
                                        <button type="button" class="primary-btn small bg-primary border-0 text-white" 
                                                data-toggle="modal" data-target="#editKuralModal{{ $kural->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('thirukkural.delete', $kural->id) }}"
                                           class="primary-btn small bg-danger border-0 text-white"
                                           onclick="return confirm('Delete this Kural?')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editKuralModal{{ $kural->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Thirukkural</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('thirukkural.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $kural->id }}">
                                                        <div class="modal-body">
                                                            <div class="form-group text-left">
                                                                <label>Kural No.</label>
                                                                <input type="number" name="kural_no" class="form-control" value="{{ $kural->kural_no }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Section (Paal)</label>
                                                                <input type="text" name="section" class="form-control" value="{{ $kural->section }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Chapter (Adhikaram)</label>
                                                                <input type="text" name="chapter" class="form-control" value="{{ $kural->chapter }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Kural (Tamil)</label>
                                                                <textarea name="kural_tamil" class="form-control" rows="3">{{ $kural->kural_tamil }}</textarea>
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Kural (English)</label>
                                                                <textarea name="kural_english" class="form-control" rows="3">{{ $kural->kural_english }}</textarea>
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Explanation</label>
                                                                <textarea name="explanation" class="form-control" rows="3">{{ $kural->explanation }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="primary-btn fix-gr-bg">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-book-open fa-2x mb-2 d-block"></i> No Kurals added yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="white-box mt-20" style="background: linear-gradient(135deg,#8b5e3c22,#f5e6d022); border-left: 4px solid #8b5e3c;">
                    <p class="mb-0" style="color:#8b5e3c; font-style:italic;">
                        <strong>திருக்குறள் (Thirukkural)</strong> is a classic Tamil text consisting of 1,330 short couplets (kurals) grouped into 133 chapters. Written by Thiruvalluvar, it presents the virtues of a virtuous life, wealth, and love.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#kuralTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
