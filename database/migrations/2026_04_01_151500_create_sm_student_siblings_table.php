<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmStudentSiblingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_student_siblings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('sibling_id')->unsigned()->nullable();
            $table->string('sibling_name')->nullable();
            $table->string('sibling_relation')->nullable(); // Brother / Sister
            $table->tinyInteger('is_studying_in_school')->default(0);
            $table->integer('class_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('school_id')->default(1)->unsigned();
            $table->integer('academic_id')->default(1)->unsigned();
            $table->timestamps();

            // $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_student_siblings');
    }
}
