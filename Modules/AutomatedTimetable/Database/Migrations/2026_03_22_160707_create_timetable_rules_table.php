<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->integer('required_periods')->default(1);
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
        Schema::dropIfExists('timetable_rules');
    }
}
