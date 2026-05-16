<?php

namespace Modules\Jitsi\Http\Controllers\Api;

use App\ApiBaseMethod;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Jitsi\Entities\JitsiMeeting;
use Modules\Jitsi\Entities\JitsiVirtualClass;


class JitsiApiController extends Controller
{
    public function index($id = null)
    {
        if (Auth::user()->role_id == 4) {

            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')->whereHas('teachers', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })->get();


            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')->get();


            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } elseif (Auth::user()->role_id == 2 || Auth::user()->role_id == 3) {
            $studentRecord = StudentRecord::where('id', $id)->first();
            $class_id = $studentRecord->class_id;
            $section_id = $studentRecord->section_id;
            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')->where('class_id', $class_id)->where('section_id', $section_id)->orwhere('section_id', null)->get();


            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        } else {
            $meetings = JitsiVirtualClass::orderBy('id', 'DESC')->with('section', 'section.students')->whereHas('section', function ($query) {
                return  $query->whereHas('students', function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                });
            })->get();


            foreach ($meetings as $meeting) {
                if (Auth::user()->role_id == 1) {
                    $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                    if (!is_null($teahcer_id)) {
                        $teahcer_id = $teahcer_id->user_id;
                    }
                } else {
                    $teahcer_id = 0;
                }
                if ($meeting->getCurrentStatusAttribute() == 'started') {

                    if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                        $meeting->status = 'started';
                    } else {
                        $meeting->status = 'join';
                    }
                } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                    $meeting->status = 'waiting';
                } else {
                    $meeting->status = 'closed';
                }
            }
            $data['meetings'] = $meetings;
        }
        return ApiBaseMethod::sendResponse($data, null);
    }


    public function meetings()
    {
        try {
            if (Auth::user()->role_id == 4) {
                $meetings =   JitsiMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
                    ->orWhere('created_by', Auth::user()->id)

                    ->get();

                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        if (!is_null($teahcer_id)) {
                            $teahcer_id = $teahcer_id->user_id;
                        }
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                $meetings =  JitsiMeeting::orderBy('id', 'DESC')->get();

                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        if (!is_null($teahcer_id)) {
                            $teahcer_id = $teahcer_id->user_id;
                        }
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            } else {
                $meetings = JitsiMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                    return  $query->where('user_id', Auth::user()->id);
                })

                    ->get();
                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('jitsi_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        if (!is_null($teahcer_id)) {
                            $teahcer_id = $teahcer_id->user_id;
                        }
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            }
            return ApiBaseMethod::sendResponse($data, null);
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }
}
