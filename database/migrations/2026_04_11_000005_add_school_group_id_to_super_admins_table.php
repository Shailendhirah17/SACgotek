<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolGroupIdToSuperAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('super_admins', function (Blueprint $table) {
            if (!Schema::hasColumn('super_admins', 'school_group_id')) {
                $table->unsignedBigInteger('school_group_id')->nullable()->after('id');
                $table->foreign('school_group_id')->references('id')->on('school_groups')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('super_admins', function (Blueprint $table) {
            $table->dropForeign(['school_group_id']);
            $table->dropColumn('school_group_id');
        });
    }
}
