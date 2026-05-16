<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSchoolGroupIdToSmSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Links existing schools to the new school_groups system.
     * Creates a "Default Group" and assigns all existing schools to it.
     *
     * @return void
     */
    public function up()
    {
        // Add school_group_id column to sm_schools
        if (!Schema::hasColumn('sm_schools', 'school_group_id')) {
            Schema::table('sm_schools', function (Blueprint $table) {
                $table->unsignedBigInteger('school_group_id')->nullable()->after('id');
            });
        }

        // Create default group and assign all existing schools
        if (Schema::hasTable('school_groups')) {
            $defaultGroupId = DB::table('school_groups')->insertGetId([
                'name' => 'Default Group',
                'code' => 'DEFAULT',
                'description' => 'Default school group for existing schools',
                'active_status' => true,
                'subscription_plan' => 'enterprise',
                'max_schools' => 9999,
                'max_students_per_school' => 9999,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign all existing schools to the default group
            DB::table('sm_schools')->update(['school_group_id' => $defaultGroupId]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sm_schools', function (Blueprint $table) {
            $table->dropColumn('school_group_id');
        });

        // Remove the default group
        DB::table('school_groups')->where('code', 'DEFAULT')->delete();
    }
}
