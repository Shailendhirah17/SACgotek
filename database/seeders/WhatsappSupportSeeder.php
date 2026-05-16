<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhatsappSupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = DB::table('sm_schools')->get();

        foreach ($schools as $school) {
            DB::table('whatsapp_support_settings')->updateOrInsert(
                ['school_id' => $school->id],
                [
                    'whatsapp_number' => '1234567890',
                    'whatsapp_title' => 'Customer Support',
                    'active_status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
