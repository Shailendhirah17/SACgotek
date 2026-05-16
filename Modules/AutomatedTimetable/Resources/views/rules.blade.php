@extends('backEnd.master')
@section('title')
Automated Timetable Rules
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Timetable Rules</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">Dashboard</a>
                <a href="#">Automated Timetable</a>
                <a href="#">Rules</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <form action="{{ route('automatedtimetable.save_rules') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-title">
                                    <h3 class="mb-30">Global Constraints</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-lg-6">
                                <div class="primary_input">
                                    <label class="primary_input_label">Max Periods Per Day (Global)</label>
                                    <input class="primary_input_field" type="number" name="rules[max_periods_per_day]" value="{{ $rules->where('rule_label', 'max_periods_per_day')->first()->rule_value ?? 8 }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="primary_input">
                                    <label class="primary_input_label">Max Same Subject Per Day</label>
                                    <input class="primary_input_field" type="number" name="rules[max_same_subject_per_day]" value="{{ $rules->where('rule_label', 'max_same_subject_per_day')->first()->rule_value ?? 2 }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-lg-6">
                                <div class="primary_input">
                                    <label class="primary_input_label">Restricted Morning Subjects (Comma separated ID)</label>
                                    <input class="primary_input_field" type="text" name="rules[morning_only_subjects]" value="{{ $rules->where('rule_label', 'morning_only_subjects')->first()->rule_value ?? '' }}" placeholder="Ex: 1, 5, 10">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="primary_input">
                                    <label class="primary_input_label">Restricted Afternoon Subjects (Comma separated ID)</label>
                                    <input class="primary_input_field" type="text" name="rules[afternoon_only_subjects]" value="{{ $rules->where('rule_label', 'afternoon_only_subjects')->first()->rule_value ?? '' }}" placeholder="Ex: 12, 15">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    Save Rules
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
