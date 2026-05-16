@extends('backEnd.master')
@section('title')
@lang('common.school_hierarchy')
@endsection
@push('css')
<style>
.tree {
    display: flex;
    justify-content: center;
}
.tree ul {
    padding-top: 20px; position: relative;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
    display: flex;
    padding-left: 0;
}

.tree li {
    float: left; text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 10px 0 10px;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

.tree li::before, .tree li::after{
    content: '';
    position: absolute; top: 0; right: 50%;
    border-top: 2px solid #ccc;
    width: 50%; height: 20px;
}
.tree li::after{
    right: auto; left: 50%;
    border-left: 2px solid #ccc;
}

.tree li:only-child::after, .tree li:only-child::before {
    display: none;
}

.tree li:only-child{ padding-top: 0;}

.tree li:first-child::before, .tree li:last-child::after{
    border: 0 none;
}

.tree li:last-child::before{
    border-right: 2px solid #ccc;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
}

.tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #ccc;
    width: 0; height: 20px;
}

.tree li a{
    border: 2px solid #ccc;
    padding: 10px 15px;
    text-decoration: none;
    color: #666;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    display: inline-block;
    border-radius: 8px;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
    background: #fff;
    min-width: 150px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.tree li a:hover, .tree li a:hover+ul li a {
    background: #7c32ff; color: #fff; border: 2px solid #7c32ff;
}

.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
    border-color:  #7c32ff;
}
.tree li a:hover small { color: #f0f0f0 !important; }

.org-card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.org-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-bottom: 8px;
    object-fit: cover;
    border: 3px solid #f2f2f2;
}
.org-role-header {
    font-weight: 600;
    font-size: 16px;
    background: #f8f9fa;
    border-bottom: 3px solid #7c32ff;
}
</style>
@endpush

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>School Hierarchy</h1>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box" style="overflow-x: auto; min-height: 500px; padding: 40px 20px;">
                    @if(isset($orgTree) && $orgTree->isNotEmpty())
                    <div class="tree">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" class="org-role-header" style="border-bottom: 3px solid #ff4b5c; margin-bottom: 10px;">
                                    @php $schoolName = generalSetting()->school_name ?? 'School Organization'; @endphp
                                    <h4 class="mb-0" style="font-weight: bold; color: inherit;">{{ $schoolName }}</h4>
                                </a>
                                <ul>
                                    @foreach($orgTree as $role)
                                    <li>
                                        <a href="javascript:void(0)" class="org-role-header">
                                            <span>{{ $role->name }}</span>
                                        </a>
                                        <ul>
                                            @foreach($role->staffs as $staff)
                                            <li>
                                                <a href="{{ Route::has('viewStaff') ? route('viewStaff', $staff->id) : '#' }}" class="org-card">
                                                    <div class="org-card-content">
                                                        @php 
                                                            $avatar = $staff->staff_photo ? $staff->staff_photo : 'public/backEnd/img/staff.jpg';
                                                        @endphp
                                                        <img src="{{ asset($avatar) }}" class="org-avatar" alt="User">
                                                        <span style="font-weight: 500;">{{ $staff->full_name }}</span>
                                                        <small style="color: #666; margin-top: 4px;">{{ $staff->designation_title }}</small>
                                                    </div>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                    @else
                    <div class="text-center">
                        <h4>No Data Available</h4>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
