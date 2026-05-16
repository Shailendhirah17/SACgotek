<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomDomainToSmSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_schools', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_schools', 'custom_domain')) {
                $table->string('custom_domain')->nullable()->after('domain');
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
        Schema::table('sm_schools', function (Blueprint $table) {
            if (Schema::hasColumn('sm_schools', 'custom_domain')) {
                $table->dropColumn('custom_domain');
            }
        });
    }
}
