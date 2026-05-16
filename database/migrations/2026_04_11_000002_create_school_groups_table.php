<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * School Groups are the organizational container managed by Ultra Super Admin.
     * Each group can contain multiple schools and has its own subscription & feature set.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('active_status')->default(true);

            // Subscription & Licensing
            $table->string('subscription_plan')->default('standard'); // standard, professional, enterprise, custom
            $table->date('subscription_start')->nullable();
            $table->date('subscription_end')->nullable();
            $table->integer('max_schools')->default(5);
            $table->integer('max_students_per_school')->default(500);
            $table->string('license_key')->nullable()->unique();

            // Feature configuration (JSON for quick access)
            $table->json('features_config')->nullable();

            // Billing / Contact
            $table->string('billing_contact_name')->nullable();
            $table->string('billing_contact_email')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('billing_phone')->nullable();

            // Tracking
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('school_groups');
    }
}
