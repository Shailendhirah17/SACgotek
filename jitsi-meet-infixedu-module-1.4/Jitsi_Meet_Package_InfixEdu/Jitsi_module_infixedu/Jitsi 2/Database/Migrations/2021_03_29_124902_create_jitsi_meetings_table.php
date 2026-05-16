<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJitsiMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jitsi_meetings', function (Blueprint $table) {
            $table->id();

            $table->integer('created_by')->nullable()->default(1);
            $table->integer('instructor_id')->nullable()->default(1);
            $table->integer('member_type')->nullable();
            $table->text('meeting_id')->nullable()->default(null);
            $table->text('topic')->nullable()->default(null);
            $table->text('description')->nullable();
            $table->text('file')->default("")->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->text('time_start_before')->nullable()->default(null);
            $table->text('date')->nullable()->default(null);
            $table->text('time')->nullable()->default(null);
            $table->text('datetime')->nullable()->default(null);
            $table->integer('duration')->nullable()->default(0)->comment('0 means unlimited');
         
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jitsi_meetings');
    }
}
