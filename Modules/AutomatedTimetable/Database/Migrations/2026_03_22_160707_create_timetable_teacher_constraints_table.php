<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableTeacherConstraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_teacher_constraints', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')->nullable();
            $table->integer('max_periods_per_day')->default(5);
            $table->integer('max_periods_per_week')->default(30);
            $table->integer('school_id')->default(1);
            $table->integer('academic_id')->default(1);
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
        Schema::dropIfExists('timetable_teacher_constraints');
    }
}
