@extends('backEnd.master')
@section('title') Hostel Meals Management @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Hostel Meals Management</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Hostel Management</a>
                <a href="#">Manage Meals</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Meal Form --}}
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-utensils mr-2 text-warning"></i> Add Meal Record</h4>
                    <form action="{{ route('hostel.meal.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Hostel <span class="text-danger">*</span></label>
                            <select name="hostel_id" class="form-control" required>
                                <option value="">-- Select Hostel --</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Meal Type <span class="text-danger">*</span></label>
                            <select name="meal_type" class="form-control" required>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="snacks">Snacks</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description / Menu</label>
                            <input type="text" name="description" class="form-control" placeholder="e.g. Idli, Sambar">
                        </div>
                        <div class="form-group">
                            <label>Price (Optional)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-plus mr-1"></i> Save Meal
                        </button>
                    </form>
                </div>
            </div>

            {{-- Meals List --}}
            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-warning"></i> Meal Log</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="mealTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Hostel</th>
                                    <th>Type</th>
                                    <th>Menu</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meals as $i => $meal)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($meal->date)->format('d M Y') }}</td>
                                    <td>{{ $meal->hostel->hostel_name ?? '—' }}</td>
                                    <td><span class="badge badge-warning text-white">{{ ucfirst($meal->meal_type) }}</span></td>
                                    <td>{{ $meal->description ?? '—' }}</td>
                                    <td>{{ $meal->price ? '₹' . number_format($meal->price, 2) : '—' }}</td>
                                    <td>
                                        <a href="{{ route('hostel.meal.delete', $meal->id) }}"
                                           class="primary-btn small bg-danger border-0 text-white"
                                           onclick="return confirm('Delete this meal record?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No meals recorded yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#mealTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
