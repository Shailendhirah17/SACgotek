@extends('backEnd.master')
@section('title') Canteen Categories @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Food Categories</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Canteen</a>
                <a href="#">Categories</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-30">Add Category</h4>
                    <form action="{{ route('canteen.category.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Health Tag</label>
                            <select name="health_tag" class="form-control">
                                <option value="healthy">Healthy</option>
                                <option value="moderate">Moderate</option>
                                <option value="junk">Junk Food</option>
                            </select>
                        </div>
                        <button class="primary-btn fix-gr-bg mt-20">Save Category</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-30">Category List</h4>
                    <table class="table school-table-style" id="categoryTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Health Tag</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    @if($category->health_tag == 'healthy')
                                        <span class="badge badge-success">Healthy</span>
                                    @elseif($category->health_tag == 'junk')
                                        <span class="badge badge-danger">Junk</span>
                                    @else
                                        <span class="badge badge-warning">Moderate</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-primary">Active</span></td>
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
    $('#categoryTable').DataTable({ responsive: true, paging: false, searching: false });
});
</script>
@endpush
