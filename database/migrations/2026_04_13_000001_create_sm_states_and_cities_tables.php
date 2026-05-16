<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmStatesAndCitiesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 10)->unique()->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->boolean('active_status')->default(true);
            $table->timestamps();
            
            // Assuming sm_countries table might exist, soft foreign key approach if it doesn't 
            // to avoid strict constraints. 
            // $table->foreign('country_id')->references('id')->on('sm_countries')->onDelete('cascade');
        });

        Schema::create('sm_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('state_id');
            $table->boolean('active_status')->default(true);
            $table->timestamps();

            $table->foreign('state_id')->references('id')->on('sm_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_cities');
        Schema::dropIfExists('sm_states');
    }
}
