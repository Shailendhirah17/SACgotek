<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\Models\SmStudentRegistrationField;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmStudentSettingController extends Controller
{
    public function index()
    {
        try {
            $student_settings = SmStudentRegistrationField::where('school_id', Auth::user()->school_id)
                ->where('type', 1)
                ->orderBy('position', 'ASC')
                ->get();

            $system_required = SmStudentRegistrationField::where('school_id', Auth::user()->school_id)
                ->where('is_system_required', 1)
                ->pluck('field_name')
                ->toArray();

            return view('backEnd.studentInformation.student_settings', compact('student_settings', 'system_required'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function fieldShow(Request $request)
    {
        try {
            $field = SmStudentRegistrationField::find($request->filed_id);
            if ($field) {
                $field->is_show = $request->field_show;
                $field->save();
                return response()->json(['message' => 'Operation Successful']);
            }
            return response()->json(['error' => 'Field Not Found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Operation Failed'], 500);
        }
    }

    public function fieldSwitch(Request $request)
    {
        try {
            $field = SmStudentRegistrationField::find($request->filed_id);
            if ($field) {
                $type = $request->type;
                if ($type == 'required') {
                    $field->is_required = $request->field_status;
                } elseif ($type == 'student') {
                    $field->student_edit = $request->field_status;
                } elseif ($type == 'parent') {
                    $field->parent_edit = $request->field_status;
                }
                $field->save();
                return response()->json(['message' => 'Operation Successful']);
            }
            return response()->json(['error' => 'Field Not Found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Operation Failed'], 500);
        }
    }
}
