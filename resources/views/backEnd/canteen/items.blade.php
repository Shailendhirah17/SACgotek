@extends('backEnd.master')
@section('title') Canteen Items @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Menu Items</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Canteen</a>
                <a href="#">Menu Items</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="white-box">
                    <h4 class="mb-30">Add Item</h4>
                    <form action="{{ route('canteen.item.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Selling Price ($) <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Cost Price ($)</label>
                            <input type="number" name="cost_price" step="0.01" class="form-control" value="0">
                        </div>
                        <div class="form-group mt-20">
                            <label class="d-block">Dietary Type</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="veg" name="is_vegetarian" value="1" class="custom-control-input" checked>
                                <label class="custom-control-label" for="veg">Vegetarian</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="nonveg" name="is_vegetarian" value="0" class="custom-control-input">
                                <label class="custom-control-label" for="nonveg">Non-Veg</label>
                            </div>
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Save Item</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <h4 class="mb-30">Item List</h4>
                    <table class="table school-table-style" id="itemTable">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Dietary</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->category->name ?? 'N/A' }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>
                                    @if($item->is_vegetarian)
                                        <span class="badge badge-success"><i class="fas fa-leaf"></i> Veg</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-drumstick-bite"></i> Non-Veg</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_available)
                                        <span class="badge badge-primary">Available</span>
                                    @else
                                        <span class="badge badge-secondary">Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#itemTable').DataTable({ responsive: true });
});
</script>
@endpush
