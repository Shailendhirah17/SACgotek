<?php
use App\SmLanguagePhrase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\Jitsi\Entities\JitsiSetting;
use Modules\MenuManage\Entities\Sidebar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;

class CreateJitsiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('jitsi_settings', function (Blueprint $table) {
            $table->id();
            $table->string('jitsi_server')->default('https://meet.jit.si/');            
            $table->timestamps();
        });

        $s = new JitsiSetting();      
        $s->jitsi_server = 'https://meet.jit.si/';      
        $s->save();

        try {
  
  
             $jitsi_109 = InfixModuleStudentParentInfo::where('id',109)->where('module_id',2030)->where('user_type',2)->first(); 
             $jitsi_110 = InfixModuleStudentParentInfo::where('id',110)->where('module_id',2030)->where('user_type',2)->first();

            
            if($jitsi_109){
                $jitsi_109 =InfixModuleStudentParentInfo::find(109);  
                $jitsi_109->route = "jitsi/virtual-class/child/{id}";             
                $jitsi_109->save();
            }


            if($jitsi_110){

                $jitsi_110 = InfixModuleStudentParentInfo::find(110);             
                $jitsi_110->route = "jitsi/meetings/parent";            
                $jitsi_110->save();
            }

            $admins = [816,817,818,819,820,821,822,823,824,825,826,827,828,829,830];

            foreach ($admins as $key => $value) {
                $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',5)->first();
                if(empty($check)){
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission ->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 5;
                    $permission->save();
                 }
            }

          

            $teachers= [816,817,818,819,820,821,822,823,824,825,826,827,828,829,830];

            foreach ($teachers as $key => $value) {
            $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',4)->first();
                    if(empty($check)){
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission ->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 4;
                    $permission->save();
                  }
             
            }
          
            $receiptionists=[816,822,826];
             foreach ($receiptionists as $key => $value) {
                $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',7)->first();
                    if(empty($check)){
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission ->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 7;
                    $permission->save();
                  }
               
            }

         

            $librarians=[816,822,826];

         foreach ($librarians as $key => $value) {
                $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',8)->first();
                    if(empty($check)){
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission ->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 8;
                    $permission->save();
                 } 
              
            }
        

          $drivers = [816,822,826];
            foreach ($drivers as $key => $value) {
                 $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',8)->first();
                    if(empty($check)){
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission ->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 9;
                    $permission->save();
              }
            }
          
            $accountants=[816,822,826];
             foreach ($accountants as $key => $value) {
                $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',6)->first();
                   if(empty($check)){
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = $value;
                    $permission ->module_info = InfixModuleInfo::find($value)->name;
                    $permission->role_id = 6;
                    $permission->save();
                }   
            
            }

             
                  $students = [816,817,821];
                    foreach ($students as $key => $value) {
                        $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',2)->first();
                         if(empty($check)){
                            $permission = new InfixPermissionAssign();
                            $permission->module_id = $value;
                            $permission ->module_info = InfixModuleInfo::find($value)->name;
                            $permission->role_id = 2;
                            $permission->save();
                        }
                        
                    }

            
                 $parents = [108,109,110];
                foreach ($parents as $key => $value) {
                    $check=InfixPermissionAssign::where('module_id',$value)->where('role_id',3)->first();
                     if(empty($check)){
                        $permission = new InfixPermissionAssign();
                        $permission->module_id = $value;
                        $permission ->module_info = InfixModuleStudentParentInfo::find($value)->name;
                        $permission->role_id = 3;
                        $permission->save();
                    }
                    
                }


                  $d = [
                        [18, 'jitsi', 'Jitsi', 'Jitsi', 'জিতসী', 'Jitsi'],
                        [18, 'jitsi_virtual_class', 'Jitsi', 'Jitsi', 'জিতসী', 'Jitsi'],
                        [18, 'virtual_class', 'Virtual Class', 'Virtual Class', 'ভার্চুয়াল ক্লাস', 'Virtual Class'],
                        [18, 'topic', 'Topic', 'Topic', 'বিষয়', 'Topic'],
                        [18, 'description', 'Description', 'Description', 'বর্ণনা', 'Description'],
                        [18, 'date_of_meeting', 'Date of Meeting', 'Date of Meeting', 'সভার তারিখ', 'Date of Meeting'],
                        [18, 'time_of_meeting', 'Time of Meeting', 'Time of Meeting', 'সভার সময়', 'Time of Meeting'],
                        [18, 'meeting_durration', 'Meetting Durration (Minutes)', 'Meetting Durration (Minutes)', 'সভার সময়কাল', 'Meetting Durration (Minutes)'],
                  
                        [18, 'start_join', 'Join/Start', 'Join/Start', 'যোগ দিন / শুরু করুন', 'Join/Start'],
                        [18, 'start', 'Start', 'Start', 'শুরু', 'Start'],
                        [18, 'join', 'Join', 'Join', 'যোগ দিন', 'Join'],
                        [18, 'show', 'Show', 'Show', 'প্রদর্শন', 'Show'],
                        [18, 'delete_meetings', 'Delete Meeting', '', '', ''],
                        [18, 'are_you_sure_delete', 'Are you sure to delete ?', 'Are you sure to delete', 'আপনি কি মুছে ফেলার বিষয়ে নিশ্চিত', 'Are you sure to delete'],         
                        [18, 'join_meeting', 'Join Meeting', 'Join Meeting', 'সভাতে যোগদান করুন', 'Join Meeting'],
                        [18, 'attached_file', 'Attached File ', 'Attached File', 'সংযুক্ত ফাইল', 'Attached File'],
                        [18, 'start_date_time', 'Start Date & Time', 'Start Date & Time', 'আরম্ভের তারিখ ও সময়', 'Start Date & Time'],
                        [18, 'not_yet_start', 'Not Yet Start', 'Not Yet Start', 'এখনও না শুরু', 'Not Yet Start'],
                        [18, 'closed', 'Closed', 'Closed', 'বন্ধ', 'Closed'],       
                        [18, 'timezone', 'Timezone', 'Timezone', 'সময় অঞ্চল', 'Timezone'],
                        [18, 'created_at', 'Created At', 'Created At', 'নির্মিত', 'Created At'],
                        [18, 'delete_virtual_meeting', 'Delete virtaul meeting', 'Delete virtaul meeting', 'ভার্চুয়াল মিটিং মুছুন', 'Delete virtaul meeting'],        
                        [18, 'meeting', 'Meeting', 'Meeting', 'সভা', 'Meeting'],
                        [18, 'join_class', 'Join Class', 'Join Class', 'ক্লাসে যোগদান করুন', 'Join Class'],
                        [18, 'participants', 'Participants', 'Participants', 'অংশগ্রহণকারীরা', 'Participants'],        
                        [18, 'select_member', ' Select Member', 'Select Member', 'সিলেক্ট মেম্বার', 'Select Member'],        
                        [18, 'class_reports', 'Class Reports', 'Class Reports', 'ক্লাস রিপোর্ট', 'Class Reports'],
                        [18, 'before', 'Before', 'antaŭe', 'আগে', 'avant que'], 
                        [18, 'meeting_reports', 'Meeting Reports', 'Meeting Reports', 'সভা রিপোর্ট', 'Meeting Reports'],
                        [18, 'date_of_class', 'Date of class', 'Date of class', 'ক্লাসের তারিখ', 'Date of class'],
                        [18, 'time_of_class', 'Time of class', 'Time of class', 'ক্লাসের সময়', 'Time of class'],
                        [18, 'duration_of_class', 'Duration of class', 'Duration of class', 'ক্লাসের সময়কাল', 'Duration of class'],
                        [18, 'virtual_class_id', 'VClass ID', 'VClass ID', 'ক্লাস আইডি', 'VClass ID'],
                        [18, 'virtual_meeting', 'Virtual Meeting', 'Virtual Meeting', 'ভার্চুয়াল সভা', 'Virtual Meeting']
           
                         ];
                    foreach ($d as $row) {
                        $s = SmLanguagePhrase::where('default_phrases', trim($row[1]))->first();
                        if (empty($s)) {
                            $s = new SmLanguagePhrase();
                        }
                        $s->modules = $row[0];
                        $s->default_phrases = trim($row[1]);
                        $s->en = trim($row[2]);
                        $s->es = trim($row[3]);
                        $s->bn = trim($row[4]);
                        $s->fr = trim($row[5]);
                        $s->save();
                    }

                    $jitis_ids = [816,817,818,819,820,821,822,823,824,825,826,827,828,829,830,831,832];


            if (moduleStatusCheck('SaasRolePermission') == true) 
            {
                $all_modules = InfixModuleInfo::query();

                if (moduleStatusCheck('Jitsi')== FALSE) {
                    $all_modules->where('module_id','!=',30);
                }
                $all_modules =  $all_modules->where('module_id','!=',1)->where('active_status', 1)
                                ->whereIn('id',$jitis_ids)
                                ->whereNotIn('name',['add','edit','delete','download','print','view','Import Student'])                               
                                ->get();
            }else{
                $all_modules = InfixModuleInfo::query();
              
                 $all_modules->where('module_id',30);
               
                $all_modules = $all_modules->where('module_id','!=',1)
                                ->whereIn('id',$jitis_ids)
                                ->whereNotIn('name',['add','edit','delete','download','print','view','Import Student'])
                                ->where('is_saas',0)
                                ->get();  
            }     

            if ($all_modules) {
                foreach ($all_modules as $key=>$module) {
                    $name=strtolower(str_replace(' ','_',$module->name));
                    $name=str_replace(['_Menu','_module','_Module','_menu'],'',$name);

                    $idCheck= Sidebar::where('infix_module_id',$module->id)->first();

                    if(!$idCheck){
                        $sidebar = new Sidebar();           
                        $sidebar->name=$module->name ?? 'no name';
                        $sidebar->icon_class=$module->icon_class;
                        $sidebar->lan_name=$module->lang_name;
                        $sidebar->module_id =$module->module_id;
                        $sidebar->parent_id =$module->parent_id;
                        $sidebar->infix_module_id = $module->id;
                        $sidebar->route=$module->route;        
                        $sidebar->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::info($th);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jitsi_settings');
    }
}
