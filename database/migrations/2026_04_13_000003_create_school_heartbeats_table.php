<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolHeartbeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_heartbeats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('school_id')->index();
            $table->unsignedBigInteger('school_group_id')->nullable()->index();
            $table->timestamp('last_activity_at')->nullable();
            $table->integer('daily_active_users')->default(0);
            $table->float('system_load')->default(0); // Optional: average page load time or usage rate
            $table->string('health_status')->default('good'); // good, at-risk, inactive
            $table->float('churn_risk_score')->default(0.0); // 0.0 to 1.0 (1.0 = high risk)
            $table->timestamps();

            // Foreign keys if necessary
            // $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_heartbeats');
    }
}
