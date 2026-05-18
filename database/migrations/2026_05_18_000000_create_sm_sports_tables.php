<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmSportsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create Student Sports Selection Table
        Schema::create('sm_student_sports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id')->index();
            $table->string('sport_name');
            $table->tinyInteger('is_custom')->default(0);
            $table->unsignedInteger('school_id')->default(1)->index();
            $table->unsignedInteger('academic_id')->default(1)->index();
            $table->timestamps();

            // Foreign key to sm_students
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');
        });

        // 2. Create Sports Training Schedules Table
        Schema::create('sm_sports_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('sport_name')->index();
            $table->string('title');
            $table->date('session_date');
            $table->string('session_time'); // e.g. "04:00 PM - 06:00 PM"
            $table->string('venue');
            $table->unsignedInteger('school_id')->default(1)->index();
            $table->unsignedInteger('academic_id')->default(1)->index();
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
        Schema::dropIfExists('sm_student_sports');
        Schema::dropIfExists('sm_sports_schedules');
    }
}
