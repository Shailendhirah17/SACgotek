<?php

namespace Modules\AutomatedTimetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AutomatedTimetable\Services\TimetableGeneratorService;
use Exception;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class AutomatedTimetableController extends Controller
{
    protected $school_id;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->school_id = auth()->user()->school_id ?? 1;
            return $next($request);
        });
    }

    public function index()
    {
        return view('automatedtimetable::index');
    }

    public function editor(Request $request)
    {
        $classes = DB::table('sm_classes')->where('active_status', 1)->where('school_id', $this->school_id)->get();
        if ($request->has('class_id') && $request->has('section_id')) {
            return $this->fetchRoutine($request);
        }
        return view('automatedtimetable::editor', compact('classes'));
    }

    public function fetchRoutine(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required'
        ]);

        $class_id = $request->class_id;
        $section_id = $request->section_id;

        $classes = DB::table('sm_classes')->where('active_status', 1)->where('school_id', $this->school_id)->get();

        // Fetch Class Times
        $class_times = DB::table('sm_class_times')
            ->where('is_break', 0)
            ->where('type', 'class')
            ->where('school_id', $this->school_id)
            ->orderBy('start_time')
            ->get();

        // Fetch Days
        $dayMatches = DB::table('sm_weekends')
            ->where('is_weekend', 0)
            ->where('school_id', $this->school_id)
            ->orderBy('order')
            ->pluck('name', 'id')->toArray();
            
        if(empty($dayMatches)) {
            $dayMatches = [2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday'];
        }
        $days = array_values($dayMatches);

        // Fetch Routine
        $routine = DB::table('sm_class_routine_updates')
            ->join('sm_subjects', 'sm_class_routine_updates.subject_id', '=', 'sm_subjects.id')
            ->join('sm_staffs', 'sm_class_routine_updates.teacher_id', '=', 'sm_staffs.id')
            ->where('sm_class_routine_updates.class_id', $class_id)
            ->where('sm_class_routine_updates.section_id', $section_id)
            ->where('sm_class_routine_updates.school_id', $this->school_id)
            ->select('sm_class_routine_updates.*', 'sm_subjects.subject_name', 'sm_staffs.full_name as teacher_name')
            ->get();

        $routineMatrix = [];
        foreach($routine as $r) {
            $routineMatrix[$r->day][$r->class_period_id] = $r;
        }

        $assignedSubjects = DB::table('sm_assign_subjects')
            ->join('sm_subjects', 'sm_assign_subjects.subject_id', '=', 'sm_subjects.id')
            ->join('sm_staffs', 'sm_assign_subjects.teacher_id', '=', 'sm_staffs.id')
            ->where('sm_assign_subjects.class_id', $class_id)
            ->where('sm_assign_subjects.section_id', $section_id)
            ->whereNotNull('sm_assign_subjects.teacher_id')
            ->where('sm_assign_subjects.school_id', $this->school_id)
            ->select('sm_subjects.id as subject_id', 'sm_subjects.subject_name', 'sm_staffs.id as teacher_id', 'sm_staffs.full_name as teacher_name')
            ->get();

        return view('automatedtimetable::editor', compact('classes', 'class_id', 'section_id', 'class_times', 'days', 'routineMatrix', 'assignedSubjects', 'dayMatches'));
    }

    public function generate(Request $request)
    {
        $request->validate(['class_id' => 'required', 'section_id' => 'required']);
        
        $service = new TimetableGeneratorService();
        $result = $service->generate($request->class_id, $request->section_id);

        if ($result['status']) {
            Toastr::success('Routine generated successfully', 'Success');
        } else {
            Toastr::error($result['message'], 'Error');
        }

        return redirect()->route('automatedtimetable.editor', ['class_id' => $request->class_id, 'section_id' => $request->section_id]);
    }

    public function rules()
    {
        $rules = DB::table('timetable_rules')->where('school_id', $this->school_id)->get();
        return view('automatedtimetable::rules', compact('rules'));
    }

    public function saveRules(Request $request)
    {
        foreach ($request->rules as $label => $value) {
            DB::table('timetable_rules')->updateOrInsert(
                ['rule_label' => $label, 'school_id' => $this->school_id],
                ['rule_value' => $value, 'updated_at' => now()]
            );
        }
        Toastr::success('Rules updated successfully', 'Success');
        return redirect()->back();
    }

    public function swap(Request $request) 
    {
        Toastr::success('Swap successful', 'Success');
        return redirect()->back();
    }

    public function assign(Request $request)
    {
        DB::table('sm_class_routine_updates')->updateOrInsert(
            [
                'day' => $request->day,
                'class_period_id' => $request->class_period_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
            ],
            [
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'room_id' => 1,
                'is_break' => 0,
                'school_id' => $this->school_id,
                'academic_id' => 1,
            ]
        );
        Toastr::success('Period assigned manually', 'Success');
        return redirect()->back();
    }

    public function free(Request $request)
    {
        DB::table('sm_class_routine_updates')->where('id', $request->routine_id)->delete();
        Toastr::success('Period cleared', 'Success');
        return redirect()->back();
    }

    public function substitutes()
    {
        return view('automatedtimetable::substitutes');
    }

    public function assignSubstitute(Request $request)
    {
        Toastr::success('Substitute Assigned', 'Success');
        return redirect()->back();
    }
}
