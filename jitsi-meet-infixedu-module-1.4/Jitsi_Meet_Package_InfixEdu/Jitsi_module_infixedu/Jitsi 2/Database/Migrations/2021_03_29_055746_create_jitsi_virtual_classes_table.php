<?php

use App\InfixModuleManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJitsiVirtualClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jitsi_virtual_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable()->default(1);           
            $table->text('meeting_id')->nullable()->default(null);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('class_id')->nullable();
            $table->string('section_id')->nullable();
            $table->string('subject_id')->nullable();          
            $table->text('topic')->nullable()->default(null);
            $table->text('description')->nullable();
            $table->text('time_start_before')->nullable()->default(null);
            $table->integer('duration')->nullable()->default(0)->comment('0 means unlimited');
            $table->text('date')->nullable()->default(null);
            $table->text('time')->nullable()->default(null);
            $table->text('datetime')->nullable()->default(null);
            $table->text('attached_file')->nullable();
            $table->timestamps();
        });


        //jitsi version update 
        $module = InfixModuleManager::where('name','Jitsi')->first();
            if($module){
                $module->version = "1.0";
                $module->save();
            }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jitsi_virtual_classes');
    }
}
