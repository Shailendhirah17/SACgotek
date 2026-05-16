<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeoColumnsToSmSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_schools', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_schools', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('sm_schools', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (!Schema::hasColumn('sm_schools', 'state_id')) {
                $table->unsignedBigInteger('state_id')->nullable();
                // $table->foreign('state_id')->references('id')->on('sm_states')->onDelete('set null');
            }
            if (!Schema::hasColumn('sm_schools', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable();
                // $table->foreign('city_id')->references('id')->on('sm_cities')->onDelete('set null');
            }
            // Ensure region mapping is an integer or string based on previous schema, 
            // since region exists as an integer, we might leave it or use a separate region mapping table
            // However, the base migration has $blueprint->integer('region')->nullable(); 
            // We just leave region as is, but we might want region_id if standardizing.
            if (!Schema::hasColumn('sm_schools', 'region_id')) {
                $table->unsignedBigInteger('region_id')->nullable();
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
            $table->dropColumn(['latitude', 'longitude', 'state_id', 'city_id', 'region_id']);
        });
    }
}
