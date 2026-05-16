@extends('backEnd.master')
@section('title') Purchase Orders @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Purchase Orders</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Vendor &amp; Accounts</a>
                <a href="#">Purchase Orders</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Create PO Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-shopping-cart mr-2 text-warning"></i> Create Purchase Order</h4>
                    <form action="{{ route('purchase-order.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Vendor <span class="text-danger">*</span></label>
                            <select name="vendor_id" class="form-control" required>
                                <option value="">-- Select Vendor --</option>
                                @foreach($vendors ?? [] as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Order Date <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Items Description</label>
                            <textarea name="items_description" class="form-control" rows="4" placeholder="List items, quantities, rates..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Total Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="total_amount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-plus mr-1"></i> Create Order
                        </button>
                    </form>
                </div>
            </div>

            {{-- PO List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-warning"></i> Purchase Orders</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="poTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>PO No.</th>
                                    <th>Vendor</th>
                                    <th>Order Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchase_orders as $i => $po)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><span class="badge badge-secondary">PO-{{ str_pad($po->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                    <td>{{ $po->vendor->vendor_name ?? '—' }}</td>
                                    <td>{{ $po->order_date ? \Carbon\Carbon::parse($po->order_date)->format('d M Y') : '—' }}</td>
                                    <td><strong>₹{{ number_format($po->total_amount, 2) }}</strong></td>
                                    <td>
                                        @php
                                            $badge = match($po->status) {
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'completed' => 'info',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badge }}">{{ ucfirst($po->status) }}</span>
                                    </td>
                                    <td>
                                        @if($po->status === 'pending')
                                            <a href="{{ route('purchase-order.status', [$po->id, 'approved']) }}"
                                               class="primary-btn small fix-gr-bg"
                                               onclick="return confirm('Approve this PO?')">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="{{ route('purchase-order.status', [$po->id, 'rejected']) }}"
                                               class="primary-btn small bg-danger border-0"
                                               onclick="return confirm('Reject this PO?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('purchase-order.delete', $po->id) }}"
                                           class="primary-btn small bg-secondary border-0"
                                           onclick="return confirm('Delete this purchase order?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No purchase orders yet.
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
    $('#poTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush
