<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperAdminAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_admin_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('super_admin_id');
            $table->string('action'); // created, updated, deleted, login, logout, status_changed, module_toggled
            $table->string('entity_type')->nullable(); // SuperAdmin, School, Subscription, Setting, Module
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('super_admin_id')
                  ->references('id')
                  ->on('super_admins')
                  ->onDelete('cascade');

            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('super_admin_audit_logs');
    }
}
