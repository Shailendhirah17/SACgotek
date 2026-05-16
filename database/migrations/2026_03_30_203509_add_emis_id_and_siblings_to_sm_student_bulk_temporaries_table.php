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
        Schema::table('student_bulk_temporaries', function (Blueprint $table) {
            $table->string('emis_id', 200)->nullable();
            $table->tinytext('siblings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_bulk_temporaries', function (Blueprint $table) {
            $table->dropColumn('emis_id');
            $table->dropColumn('siblings');
        });
    }
};
