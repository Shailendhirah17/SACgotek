<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolGroupFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Granular feature toggles per school group.
     * Ultra Super Admin can enable/disable individual features for each group.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_group_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_group_id');
            $table->string('feature_key'); // e.g., 'exam_portal', 'chat', 'transport'
            $table->string('feature_name'); // Human-readable name
            $table->boolean('is_enabled')->default(false);
            $table->json('config')->nullable(); // Additional feature-specific config
            $table->timestamps();

            $table->foreign('school_group_id')
                  ->references('id')
                  ->on('school_groups')
                  ->onDelete('cascade');

            $table->unique(['school_group_id', 'feature_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_group_features');
    }
}
