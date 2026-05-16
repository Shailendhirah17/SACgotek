<?php

namespace Modules\Jitsi\Http\Controllers;

use App\User;
use App\SmClass;
use Carbon\Carbon;
use App\SmNotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Jitsi\Entities\JitsiMeeting;
use Modules\Jitsi\Entities\JitsiMeetingUser;
use Modules\Jitsi\Entities\JitsiSetting;
use Modules\VirtualClass\Entities\VirtualClass;
use Modules\RolePermission\Entities\InfixRole;
use Modules\Jitsi\Entities\JitsiVirtualClass;
use Illuminate\Support\Facades\DB;
class JitsiMeetingController extends Controller
{

  
    public function index()
    {


        $data['user'] = Auth::user();
         $data['roles'] = InfixRole::where(function ($q) {
            $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
             })->whereNotIn('id', [1, 2])->get();
        $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get();
         $data['classes'] =SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

        if (Auth::user()->role_id == 4) {
            $data['default_settings'] = JitsiSetting::first();
            $data['meetings'] = JitsiMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })
                ->orWhere('created_by', Auth::user()->id)
               
                ->get();
        } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
            $data['meetings'] = JitsiMeeting::orderBy('id', 'DESC')->get();
        } else {
            $data['meetings'] = JitsiMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return  $query->where('user_id', Auth::user()->id);
            })
               
                ->get();
        }

        return view('jitsi::meeting.meeting', $data);
    }



    public function store(Request $request)
    {

       
        $instructor_id = $request->get('instructor_id');

        $member_type = $request->get('member_type');
        $topic = $request->get('topic');
        $description = $request->get('description');
        $date = $request->get('date');
        $time = $request->get('time');
        $time_start_before = $request->get('time_start_before');
        $duration = $request->get('duration');
        $description=$request->get('description');
        $datetime = $date . " " . $time;
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));

        if ($request->file('attached_file') != "") {
            $file = $request->file('attached_file');
            $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/jitsi-meeting/', $fileName);
            $fileName = 'public/uploads/jitsi-meeting/' . $fileName;
        }else{
             $fileName="";
        }



        $request->validate([
            'member_type' => 'required',
            'participate_ids' => 'required|array',
            'topic' => 'required',         
            'date' => 'required',
            'time' => 'required',
            'duration'=>'required',

        ]);


        try {


            $local_meeting = JitsiMeeting::create([
                'meeting_id' => date('ymdhmi'),
                'member_type' => $member_type,  
                'instructor_id' => $instructor_id,           
                'topic' => $topic,
                'date' => $date,
                'time' => $time,
                'datetime' => $datetime,
                'description' => $description,
                'file' =>$fileName,
                'duration' => $duration,
                'time_start_before'=>$time_start_before,
                'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                'end_time' =>  Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
                'created_by' => Auth::user()->id,

            ]);


            $local_meeting->participates()->attach($request['participate_ids']);
            $this->setNotificaiton($request['participate_ids'], $request['member_type'], $updateStatus = 0);
            DB::commit();

            if ( $local_meeting) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

            Toastr::success('Class updated successful', 'Success');
            return redirect()->route('jitsi.meetings');
        } catch (\Exception $e) {
        
             Toastr::error($e->getMessage(), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function show(int $id)
    {

        $localMeetingData = JitsiMeeting::findOrFail($id);

        return view('jitsi::meeting.meetingDetails', compact('localMeetingData'));
    }

    public function edit(int $id)
    {

       
        $data['setting'] = JitsiSetting::first();
        $data['user'] = Auth::user();
        $data['roles'] = InfixRole::where(function ($q) {
            $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
             })->whereNotIn('id', [1, 2])->get();
         $data['editdata'] = JitsiMeeting::findOrFail($id);
         $data['meetings'] = JitsiMeeting::orderBy('id', 'DESC')->get();
         $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get();
         $data['user_type'] = $data['editdata']->participates[0]['role_id'];   
         $data['classes'] =SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();


        $data['participate_ids'] = DB::table('jitsi_meeting_users')->where('meeting_id',$id)->select('user_id')->pluck('user_id');

             $data['userList'] = User::where('role_id', $data['user_type'])
                ->where('school_id', Auth::user()->school_id)
                ->whereIn('id',$data['participate_ids'])
                ->select('id', 'full_name', 'role_id', 'school_id')->get();
            if (Auth::user()->role_id != 1) {
                if (Auth::user()->id != $data['editdata']->created_by) {
                    Toastr::error('Meeting is created by other, you could not modify !', 'Failed');
                    return redirect()->back();
                }
            }

        return view('jitsi::meeting.meeting', $data);
    }

    public function update(Request $request, int $id)
    {
       
        $topic = $request->get('topic');
        $member_type = $request->get('member_type');
        $description = $request->get('description');
        $instructor_id = $request->get('instructor_id');
        $class_id = $request->get('class_id');
        $date = $request->get('date');
        $time = $request->get('time');
        $datetime = $date . " " . $time;
        $datetime = strtotime($datetime);
        $time_start_before =$request->get('time_start_before');
        $duration = $request->get('duration');
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));

        $request->validate([
                      
            'topic' => 'required',
            'date' => 'required',
            'time' => 'required',

        ]);

       $system_meeting=JitsiMeeting::updateOrCreate([
            'id' => $id
        ], [
  
                'member_type' => $member_type,  
                'instructor_id' => $instructor_id,           
                'topic' => $topic,
                'date' => $date,
                'time' => $time,
                'datetime' => $datetime,
                'description' => $description, 
                'time_start_before'=>$time_start_before,
                'duration' => $duration,
                'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                'end_time' =>  Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
                'created_by' => Auth::user()->id,

        ]);
     if ($request->file('attached_file') != "") {
                if (file_exists($system_meeting->logo)) {
                    unlink($system_meeting->logo);
                }
                $file = $request->file('attached_file');
                $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/jitsi-meeting/', $fileName);
                $fileName = 'public/uploads/jitsi-meeting/' . $fileName;
                $system_meeting->update([
                  'file' =>$fileName,
                ]);
            }

        if (auth()->user()->role_id == 1) {
            $system_meeting->participates()->detach();
            $system_meeting->participates()->attach($request['participate_ids']);
        }
        $this->setNotificaiton($request['participate_ids'], $request['member_type'], $updateStatus = 1);

        DB::commit();
        Toastr::success('Meeting updated successful', 'Success');
        return redirect()->route('jitsi.meetings');

    }


    public function destroy(int $id)
    {
        $meeting = JitsiMeeting::findOrFail($id);
        $meeting->delete();
      
        return redirect()->route('jitsi.meetings');

        $meeting = JitsiMeeting::findOrFail($id);
        JitsiMeetingUser::where('meeting_id', $meeting->id)->delete();

        $meeting->delete();
        Toastr::success('Class Delete successful', 'Success');
        return redirect()->route('jitsi.meetings');
    }

    public function meetingStart($meeting_id)
    {
            $meeting = JitsiMeeting::where('meeting_id', $meeting_id)->first();
            $setting = JitsiSetting::first();
            return view('jitsi::meeting.start', compact('meeting', 'setting'));


    }
    public function meetingJoin($meeting_id){

            $meeting = JitsiMeeting::where('meeting_id', $meeting_id)->first();
            $setting = JitsiSetting::first();
            return view('jitsi::meeting.start', compact('meeting', 'setting'));

    }

        private function setNotificaiton($users, $role_id, $updateStatus)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $school_id = Auth::user()->school_id;
        $notification_datas = [];

        if ($updateStatus == 1) {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id'       => $user,
                        'role_id'       => $role_id,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'academic_id'     => getAcademicId(),
                        'message'       => 'jitsi meeting is updated by ' . Auth::user()->full_name . '',
                        'url'           => route('jitsi.meetings'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
            };
        } else {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id'       => $user,
                        'role_id'       => $role_id,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'academic_id'     => getAcademicId(),
                        'message'       => 'jitsi meeting is created by ' . Auth::user()->full_name . ' with you',
                        'url'           => route('jitsi.meetings'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
            };
        }
        SmNotification::insert($notification_datas);
    }

    public function userWiseUserList(Request $request)
    {
        if ($request->has('user_type')) {
            $userList =  User::where('role_id', $request['user_type'])
                ->where('school_id', Auth::user()->school_id)
                ->select('id', 'full_name', 'school_id')->get();
            return response()->json([
                'users' => $userList
            ]);
        }
    }
}
