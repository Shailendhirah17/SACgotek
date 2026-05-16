<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Starting Whatsapp Support restoration...\n";

$schools = DB::table('sm_schools')->get();

foreach ($schools as $school) {
    echo "Processing School ID: {$school->id} - {$school->school_name}\n";
    
    // Check if settings already exist for this school
    $exists = DB::table('settings')->where('school_id', $school->id)->exists();
    
    if (!$exists) {
        echo "  Inserting default Whatsapp settings for school {$school->id}...\n";
        DB::table('settings')->insert([
            'agent_type' => 'single',
            'availability' => 'both',
            'showing_page' => 'all',
            'color' => '#0dc152',
            'intro_text' => 'Our customer support team is here to answer your questions. Ask us anything!',
            'welcome_message' => 'Hi, How can I help?',
            'homepage_url' => url('/'),
            'primary_number' => '+91 9003714619',
            'open_popup' => 0,
            'disable_for_admin_panel' => 0,
            'show_unavailable_agent' => 1,
            'layout' => 1,
            'layout_preview_url' => 'whatsapp-support/preview-1.png',
            'school_id' => $school->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        echo "  Settings already exist for school {$school->id}. Skipping.\n";
    }
}

echo "Restoration complete!\n";
