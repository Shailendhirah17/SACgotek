<?php

namespace Modules\Jitsi\Http\Controllers;
use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use Carbon\Carbon;
use App\SmClassSection;
use App\SmNotification;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Jitsi\Entities\JitsiSetting;
use Illuminate\Contracts\Support\Renderable;
use Modules\Jitsi\Entities\JitsiVirtualClass;


class JitsiVirtualClassController extends Controller
{
    public function index()
    {
            $data['user'] = Auth::user();
            $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get(); 
            $data['records'] = [];
            $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first(); 

            if(Auth::user()->role_id==4){

                  $data['classes'] = SmAssignSubject::where('teacher_id',$teacher_info->id)
                                ->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                                ->where('sm_assign_subjects.academic_id', getAcademicId())
                                ->where('sm_assign_subjects.active_status', 1)
                                ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                                ->select('sm_classes.id','class_name')
                                ->groupby('class_id')
                                ->get();
                 }else{
                  $data['classes'] =SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
             }    
             
             if( $data['user']->role_id==1 || $data['user']->role_id==5){
                $data['teachers'] = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->select('id','full_name','user_id')->get();

            }elseif($data['user']->role_id==4){

                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->where('role_id', 4)->first(['id','user_id']); 
                
                $class_ids=SmAssignSubject::where('teacher_id',$teacher_info->id)->get(['class_id']);

                $section_s=SmAssignSubject::whereIn('class_id',$class_ids)->get('section_id');

                $teachers=SmAssignSubject::whereIn('section_id',$section_s)->get('teacher_id');

                $data['teachers'] = SmStaff::where('active_status', 1)->whereIn('id',$teachers)->where('user_id','!=',Auth::user()->id)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->select('id','full_name','user_id')->get();

            }
          
        if (Auth::user()->role_id == 4) {
              
                $data['virtual_classs'] = JitsiVirtualClass::orderBy('id', 'DESC')->whereHas('teachers', function ($query) {
                    return $query->where('user_id',Auth::user()->id);
                })
                    
                    ->get();
            } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                $data['virtual_classs'] = JitsiVirtualClass::orderBy('id', 'DESC')->get();
            } elseif (Auth::user()->role_id == 3) {
            
                $parent=SmParent::where('user_id',Auth::user()->id)->first();
                $student_detail = SmStudent::where('parent_id', $parent->id)->get();
                $data=['virtual_classs'];
                foreach( $student_detail as $student){
                    $class_id=$student->class_id;
                    $section_id=$student->section_id;
                    $data['virtual_classs']= JitsiVirtualClass::orderBy('id', 'DESC')->where('class_id',$class_id)->where('section_id',$section_id)->orwhere('section_id',null)->get();

                }
                 
            }elseif(Auth::user()->role_id == 2){
                $data['records'] = StudentRecord::where('student_id', auth()->user()->student->id)        ->where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())->get();
                $student = SmStudent::where('user_id', Auth::user()->id)->first();
                $class_id=$student->class_id;
                $section_id=$student->section_id;
                $data['virtual_classs']= JitsiVirtualClass::orderBy('id', 'DESC')->where('class_id',$class_id)->where('section_id',$section_id)->orwhere('section_id',null)->get();
    
            }else {
               

                $data['virtual_classs'] = JitsiVirtualClass::orderBy('id', 'DESC')->with('section', 'section.students')->whereHas('section', function ($query) {
                    return  $query->whereHas('students', function ($query) {
                        return $query->where('user_id', Auth::user()->id);
                    });
                })
                   
                    ->get();
            }
        return view('jitsi::virtualClass.virtual_class',$data);
    }


     public function myChild($id)
        {
    
            try {
               if(Auth::user()->role_id==3){
                   
                
                $data['records'] = StudentRecord::where('student_id', $id)->where('school_id', auth()->user()->school_id)->where('academic_id', getAcademicId())->get();
                return view('jitsi::virtualClass.virtual_class', $data);
               }
            } catch (\Throwable $th) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }

    public function store(Request $request)
    {
        
     
    
        if(Auth::user()->role_id==4){
            $count=count($request->teacher_ids);
            if($count>3){
                Toastr::error('Can Not Select More Than 2 Person !', 'Failed');
                return redirect()->back();
            }
        }
          if (auth()->user()->role_id == 1 || auth()->user()->role_id == 5) {
        $request->validate([
            
            'class_id' => 'required',                   
            'teacher_ids' => 'required|array',          
            'topic' => 'required',        
            'date' => 'required',
            'time' => 'required',
            'duration' =>'required',

        ]);
        }else{
            $request->validate([                    
                    'class_id' => 'required',                 
                    'topic' => 'required',                
                    'date' => 'required',
                    'time' => 'required',
                    'duration' =>'required',
                    ]);
        }

        $topic = $request->get('topic');        
        $class_id = $request->get('class_id');
        $section_id=$request->get('section');

        $date = $request->get('date');
        $time = $request->get('time');
        $time_start_before = $request->get('time_start_before');
        $duration = $request->get('duration');
        $description=$request->get('description');

        $datetime = $date . " " . $time;
        $datetime = strtotime($datetime);
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));


        if ($request->file('attached_file') != "") {
            $file = $request->file('attached_file');
            $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/jitsi-meeting/', $fileName);
            $fileName = 'public/uploads/jitsi-meeting/' . $fileName;
        }else{
             $fileName="";
        }




            $local_meeting = JitsiVirtualClass::create([
                    'meeting_id' => date('ymd' . rand(0, 100)),                 
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'topic' => $topic,
                    'description' =>  $request['description'],
                    'date' => $date,
                    'time' => $time,
                    'datetime' => $datetime,
                    'duration' => $duration,
                    'attached_file'=> $fileName,
                     'time_start_before' => $time_start_before,
                    'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                    'end_time' =>  Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
                    'created_by' => Auth::user()->id,

            ]);

        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 5 || auth()->user()->role_id == 4) {
            $local_meeting->teachers()->attach($request['teacher_ids']);
        } else {
            $local_meeting->teachers()->attach(Auth::user());
        }
        
        if($request->section==null){
           
            $all_sections=SmClassSection::where('class_id',$request['class_id'])->get('section_id','class_id');
            $all_section_ids=[];
            foreach($all_sections as $section){
                $all_section_ids[]=$section->section_id;
            }
         
        }else{
            $all_section_ids=array($request['section']);
        }
        
        $UserList = SmStudent::where('class_id', $request['class_id'])
            ->whereIn('section_id', $all_section_ids)
            ->where('school_id', Auth::user()->school_id)
            ->select('user_id', 'role_id', 'parent_id')->get();
        $this->setNotificaiton($UserList, $updateStatus = 0);

        if($local_meeting){
            $teacher_ids=$request['teacher_ids'];
            $created_person=User::find($local_meeting->created_by);

            if($created_person->role_id==1 || $created_person->role_id==5){
                $msg = $created_person->full_name. ' created jitsi Virtual Class For You!';
            }elseif($created_person->role_id==4){
                $msg = $created_person->full_name . ' Added you into jitsi Virtual Class !';

            }else{
                $msg = $created_person->full_name.' created jitsi Virtual Class For You!';
            }
            $users = User::whereIn('id',$teacher_ids)->where('role_id',4)->where('id','!=',Auth::user()->id)->where('school_id', 1)->get();
            foreach($users as $user){
                $notification = new SmNotification();
                $notification->message =$msg;
                $notification->is_read = 0;
                $notification->url = route('jitsi.virtual-class');
                $notification->user_id = $user->id;
                $notification->role_id = $user->role_id;
                $notification->school_id = 1;
                $notification->academic_id = $user->academic_id;
                $notification->date = date('Y-m-d');
                $notification->save();
            }
        }


        DB::commit();

        if ($local_meeting) {
            Toastr::success('Virtual class created successful', 'Success');
            return redirect()->back();
        } else {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function show($id)
    {
       try {
             $localMeetingData = JitsiVirtualClass::findOrFail($id);
              return view('jitsi::virtualClass.virtual_class_detail', compact('localMeetingData'));

            
        } catch (Exception $e) {
            
        }
    }

    public function edit($id)
    {
       try {

         $localMeeting = JitsiVirtualClass::findOrFail($id);
     

        if (Auth::user()->role_id != 1) {
            if (Auth::user()->id != $localMeeting->created_by) {
                Toastr::error('Meeting is created by other, you could not Edit !', 'Failed');
                return redirect()->back();
            }
        }
        $data['setting'] = JitsiSetting::first();
        $data['user'] = Auth::user();
        $data['editdata'] = JitsiVirtualClass::findOrFail($id);
        $data['virtual_classs'] = JitsiVirtualClass::orderBy('id', 'DESC')->get();

        if( $data['user']->role_id==1 || $data['user']->role_id==5){
            $data['teachers'] = SmStaff::where('active_status', 1)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->select('id','full_name','user_id')->get();

        }elseif($data['user']->role_id==4){

            $teacher_info=SmStaff::where('user_id',Auth::user()->id)->where('role_id', 4)->first(['id','user_id']); 
            
            $class_ids=SmAssignSubject::where('teacher_id',$teacher_info->id)->get(['class_id']);

            $section_s=SmAssignSubject::whereIn('class_id',$class_ids)->get('section_id');

            $teachers=SmAssignSubject::whereIn('section_id',$section_s)->get('teacher_id');

           
            $data['teachers'] = SmStaff::where('active_status', 1)->whereIn('id',$teachers)->where('user_id','!=',Auth::user()->id)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->select('id','full_name','user_id')->get();

        }
        $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get();
         $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first(); 

            if(Auth::user()->role_id==4){
                  $data['classes'] = SmAssignSubject::where('teacher_id',$teacher_info->id)
                                ->join('sm_classes','sm_classes.id','sm_assign_subjects.class_id')
                                ->where('sm_assign_subjects.academic_id', getAcademicId())
                                ->where('sm_assign_subjects.active_status', 1)
                                ->where('sm_assign_subjects.school_id',Auth::user()->school_id)
                                ->select('sm_classes.id','class_name')
                                ->get();
                 }else{
                  $data['classes'] =SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

                }   
     
        $data['class_sections'] = SmSection::whereIn('id', $data['editdata']->class->classSections->pluck('section_id'))->get();

        return view('jitsi::virtualClass.virtual_class', $data);
            
        } catch (Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        if(Auth::user()->role_id==4){
            $count=count($request->teacher_ids);
            if($count>3){
                Toastr::error('Can Not Select More Than 2 Person !', 'Failed');
                return redirect()->back();
            }
        }

      if (auth()->user()->role_id == 1) {
            $request->validate([
                
                'class_id' => 'required',                        
                'teacher_ids' => 'required|array',          
                'topic' => 'required',        
                'date' => 'required',
                'time' => 'required',
                 'duration' =>'required',

            ]);
            }else{
                $request->validate([
                        
                        'class_id' => 'required',                      
                        'topic' => 'required',                
                        'date' => 'required',
                        'time' => 'required',
                         'duration' =>'required',
                        ]);
            }
        $topic = $request->get('topic');        
        $class_id = $request->get('class_id');
        $section_id=$request->get('section');

        $date = $request->get('date');
        $time = $request->get('time');

        $duration = $request->get('duration');
        $description=$request->get('description');
        $time_start_before = $request->get('time_start_before');
        $datetime = $date . " " . $time;
        $datetime = strtotime($datetime);
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));


            $local_meeting = JitsiVirtualClass::updateOrCreate([
                   'id' => $id
                     ],
                    ['meeting_id' => date('ymd' . rand(0, 100)),                 
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'topic' => $topic,
                    'description' =>  $request['description'],
                    'date' => $date,
                    'time' => $time,
                    'datetime' => $datetime,
                    'duration' => $duration,
                    'time_start_before' => $time_start_before,
                    'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                    'end_time' =>  Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
                    'created_by' => Auth::user()->id,

            ]);



          if ($request->file('attached_file') != "") {
            if (file_exists($local_meeting->attached_file)) {
                unlink($local_meeting->attached_file);
            }
            $file = $request->file('attached_file');
            $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/jitsi-meeting/', $fileName);
            $fileName = 'public/uploads/jitsi-meeting/' . $fileName;
            $local_meeting->update([
                'attached_file' =>  $fileName
            ]);
        }

        if (auth()->user()->role_id == 1) {
                $local_meeting->teachers()->detach();
                $local_meeting->teachers()->attach($request['teacher_ids']);
            }

            $UserList = SmStudent::where('class_id', $request['class'])
                ->where('section_id', $request['section'])
                ->where('school_id', Auth::user()->school_id)
                ->select('user_id', 'role_id', 'parent_id')->get();
            $this->setNotificaiton($UserList, $updateStatus = 1);

            DB::commit();
            Toastr::success('Virtual Class updated successful', 'Success');
            return redirect()->route('jitsi.virtual-class');
    }


    public function destroy($id)
    {
        try {
         $localMeeting = JitsiVirtualClass::findOrFail($id);
     

        if (Auth::user()->role_id != 1) {
            if (Auth::user()->id != $localMeeting->created_by) {
                Toastr::error('Meeting is created by other, you could not DELETE !', 'Failed');
                return redirect()->back();
            }
        }

        if (file_exists($localMeeting->logo)) {
            unlink($localMeeting->logo);
        }

        $localMeeting->delete();
        $meeting_teachers=DB::table('jitsi_virtual_class_teachers')->where('meeting_id',$id)->delete();
        Toastr::success('Jitsi Virtual Class deleted successful', 'Success');
        return redirect()->route('jitsi.virtual-class');
            
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
            
        }
    }

 public function classStart($meeting_id)
    {
         try {
              
            $meeting = JitsiVirtualClass::where('meeting_id', $meeting_id)->first();
            $setting = JitsiSetting::first();
            return view('jitsi::virtualClass.start', compact('meeting', 'setting'));
    } catch (\Exception $e) {
       
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
            
        }


    }
    public function classJoin($meeting_id){

            $meeting = JitsiVirtualClass::where('meeting_id', $meeting_id)->first();
            $setting = JitsiSetting::first();
            return view('jitsi::virtualClass.start', compact('meeting', 'setting'));

    }



    private function setNotificaiton($users, $updateStatus)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $school_id = Auth::user()->school_id;
        $notification_datas = [];

        if ($updateStatus == 1) {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id'       => $user->user_id,
                        'role_id'       => 2,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'message'       => 'Jtis virtual class room details udpated',
                        'url'           => route('jitsi.virtual-class'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
                array_push(
                    $notification_datas,
                    [
                        'user_id'       => $user->parent_id,
                        'role_id'       => 3,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'message'       => 'Jtisi virtual class room details udpated of your child',
                        'url'           => route('jitsi.virtual-class'),
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
                        'user_id'       => $user->user_id,
                        'role_id'       => 2,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'message'       => 'Jitsi virtual class room created for you',
                        'url'           => route('jitsi.virtual-class'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
                array_push(
                    $notification_datas,
                    [
                        'user_id'       => $user->parent_id,
                        'role_id'       => 3,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'message'       => 'Jitsi virtual class room created for your child',
                        'url'           => route('jitsi.virtual-class'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
            };
        }
        SmNotification::insert($notification_datas);
    }

}
