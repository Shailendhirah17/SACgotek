@extends('backEnd.master')
@section('title')
Automated Timetable Editor
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Automated Timetable - Grid Editor</h1>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <form method="POST" action="{{ route('automatedtimetable.fetch') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Select Class</label>
                                <select class="primary_select form-control" name="class_id" id="class_id">
                                    <option data-display="Select Class *" value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ isset($class_id) && $class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4" id="select_section_div">
                                <label>Select Section</label>
                                <select class="primary_select form-control" name="section_id" id="section_id">
                                    <option data-display="Select Section *" value="">Select Section</option>
                                    @if(isset($section_id))
                                        <option value="{{ $section_id }}" selected>Selected Section</option>
                                    @endif
                                </select>
                                <div class="pull-right loader" id="select_section_loader" style="display:none">
                                    <img src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader" style="width: 20px;">
                                </div>
                            </div>
                            <div class="col-lg-4 mt-30">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    Fetch Routine
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(isset($routineMatrix))
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="white-box text-center mb-20">
                    <form method="POST" action="{{ route('automatedtimetable.generate') }}" style="display:inline-block">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class_id }}">
                        <input type="hidden" name="section_id" value="{{ $section_id }}">
                        <button type="submit" class="primary-btn tr-bg">
                            <span class="ti-reload"></span> Auto-Generate Timetable
                        </button>
                    </form>
                </div>
                
                <table class="display school-table school-table-style" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Day / Period</th>
                            @foreach($class_times as $time)
                                <th>{{ $time->period }} <br> <small>{{ date('h:i A', strtotime($time->start_time)) }} - {{ date('h:i A', strtotime($time->end_time)) }}</small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dayMatches as $dayId => $dayName)
                            <tr>
                                <td><strong>{{ $dayName }}</strong></td>
                                @foreach($class_times as $time)
                                    <td>
                                        @if(isset($routineMatrix[$dayId][$time->id]))
                                            @php $routine = $routineMatrix[$dayId][$time->id]; @endphp
                                            <div class="mr-10">
                                                <span class="text-primary">{{ $routine->subject_name }}</span><br>
                                                <small>{{ $routine->teacher_name }}</small><br>
                                                <form method="POST" action="{{ route('automatedtimetable.free') }}" style="display:inline-block">
                                                    @csrf
                                                    <input type="hidden" name="routine_id" value="{{ $routine->id }}">
                                                    <button type="submit" class="text-danger" style="border:none;background:none;padding:0"><small><i class="ti-trash"></i> Free</small></button>
                                                </form>
                                            </div>
                                        @else
                                            <!-- Empty Slot -->
                                            <form method="POST" action="{{ route('automatedtimetable.assign') }}">
                                                @csrf
                                                <input type="hidden" name="day" value="{{ $dayId }}">
                                                <input type="hidden" name="class_period_id" value="{{ $time->id }}">
                                                <input type="hidden" name="class_id" value="{{ $class_id }}">
                                                <input type="hidden" name="section_id" value="{{ $section_id }}">
                                                <select class="form-control" name="subject_id" required style="width:100%; border-radius:5px; padding:2px">
                                                    <option value="">Manually Assign</option>
                                                    @foreach($assignedSubjects as $as)
                                                        <option value="{{ $as->subject_id }}">{{ $as->subject_name }} ({{ $as->teacher_name }})</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="teacher_id" value="1"> 
                                                <button type="submit" class="primary-btn small tr-bg mt-2" style="padding:0 5px;">Assign</button>
                                            </form>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#class_id').on('change', function() {
            var url = "{{ route('automatedtimetable.get_sections') }}";
            var formData = { id: $(this).val() };
            
            $.ajax({
                type: "GET",
                data: formData,
                url: url,
                dataType: "json",
                beforeSend: function() {
                    $('#select_section_loader').show();
                },
                success: function(data) {
                    var a = "";
                    $.each(data, function(i, item) {
                        a += '<option value="' + item.id + '">' + item.section_name + "</option>";
                    });
                    $("#section_id").empty().append('<option value="">Select Section</option>' + a);
                    $("#section_id").niceSelect('update');
                },
                complete: function() {
                    $('#select_section_loader').hide();
                },
                error: function(data) {
                    console.log("Error:", data);
                },
            });
        });
    });
</script>
@endpush
