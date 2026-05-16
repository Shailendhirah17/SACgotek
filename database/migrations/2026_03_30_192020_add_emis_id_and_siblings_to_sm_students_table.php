<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sm_students', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_students', 'emis_id')) {
                $table->string('emis_id', 100)->nullable();
            }
            if (!Schema::hasColumn('sm_students', 'siblings')) {
                $table->text('siblings')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sm_students', function (Blueprint $table) {
            $table->dropColumn('emis_id');
            $table->dropColumn('siblings');
        });
    }
};
