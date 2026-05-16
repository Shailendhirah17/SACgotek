<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUltraSuperAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Ultra Super Admin – Master Control Layer
     * Owned exclusively by Technosprint Info Solutions.
     * Has ultimate authority over all organizations, school groups, and subscriptions.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ultra_super_admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('full_name');
            $table->string('phone_number')->nullable();
            $table->boolean('active_status')->default(true);
            $table->string('role')->default('ultra_super_admin'); // ultra_super_admin
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
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
        Schema::dropIfExists('ultra_super_admins');
    }
}
