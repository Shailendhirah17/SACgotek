<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Starting Comprehensive Configuration Restoration...\n";

$schools = DB::table('sm_schools')->get();

foreach ($schools as $school) {
    echo "Processing School ID: {$school->id} - {$school->school_name}\n";
    
    // 1. Themes Restoration
    $themeExists = DB::table('themes')->where('school_id', $school->id)->exists();
    if (!$themeExists) {
        echo "  Inserting themes for school {$school->id}...\n";
        $themeId = DB::table('themes')->insertGetId([
            'title' => 'Default',
            'is_default' => 1,
            'school_id' => $school->id,
            'created_by' => 1,
            're_style' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $colors = [
            ['name' => 'primary_color', 'value' => '#415094'],
            ['name' => 'primary_color2', 'value' => '#7c32ff'],
            ['name' => 'primary_color3', 'value' => '#7c32ff'],
            ['name' => 'primary_color4', 'value' => '#415094'],
            ['name' => 'primary_color5', 'value' => '#415094'],
            ['name' => 'primary_color6', 'value' => '#415094'],
            ['name' => 'primary_color_7', 'value' => '#415094'],
            ['name' => 'secondary_color', 'value' => '#415094'],
            ['name' => 'sidebar_bg', 'value' => '#415094'],
            ['name' => 'gradient_1', 'value' => '#415094'],
            ['name' => 'gradient_2', 'value' => '#7c32ff'],
            ['name' => 'gradient_3', 'value' => '#7c32ff'],
            ['name' => 'text_color', 'value' => '#415094'],
            ['name' => 'text_white', 'value' => '#ffffff'],
            ['name' => 'text_black', 'value' => '#000000'],
            ['name' => 'bg_white', 'value' => '#ffffff'],
            ['name' => 'bg_black', 'value' => '#000000'],
            ['name' => 'border_color', 'value' => '#415094'],
            ['name' => 'input_bg', 'value' => '#ffffff'],
            ['name' => 'success_color', 'value' => '#4cd137'],
            ['name' => 'danger_color', 'value' => '#e84118'],
            ['name' => 'warning_color', 'value' => '#fbc531'],
            ['name' => 'info_color', 'value' => '#00a8ff'],
        ];

        foreach ($colors as $color) {
            $colorRec = DB::table('colors')->where('name', $color['name'])->first();
            if ($colorRec) {
                DB::table('color_theme')->updateOrInsert(
                    ['theme_id' => $themeId, 'color_id' => $colorRec->id],
                    ['value' => $color['value'], 'school_id' => $school->id]
                );
            }
        }
    }

    // 2. Whatsapp Support Settings
    $wsExists = DB::table('settings')->where('school_id', $school->id)->exists();
    if (!$wsExists) {
        echo "  Inserting Whatsapp settings for school {$school->id}...\n";
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
    }

    // 3. Plugins (Tawk/Messenger)
    $plugins = ['tawk', 'messenger'];
    foreach ($plugins as $pluginName) {
        $pluginExists = DB::table('plugins')->where('name', $pluginName)->where('school_id', $school->id)->exists();
        if (!$pluginExists) {
            echo "  Inserting plugin '{$pluginName}' for school {$school->id}...\n";
            DB::table('plugins')->insert([
                'name' => $pluginName,
                'is_enable' => 0,
                'availability' => 'both',
                'show_admin_panel' => 0,
                'show_website' => 1,
                'showing_page' => 'all',
                'school_id' => $school->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    // 4. Registration Fields (Ensure defaults exist)
    $fieldsCount = DB::table('sm_student_registration_fields')->where('school_id', $school->id)->count();
    if ($fieldsCount == 0) {
        echo "  Seeding registration fields for school {$school->id}...\n";
        // This is a abbreviated list just to ensure the app doesn't crash
        $fields = [
            'first_name', 'last_name', 'admission_number', 'class', 'section', 'gender', 'date_of_birth'
        ];
        foreach ($fields as $field) {
            DB::table('sm_student_registration_fields')->insert([
                'field_name' => $field,
                'is_show' => 1,
                'is_required' => 1,
                'school_id' => $school->id,
            ]);
        }
    }
}

echo "All configurations restored successfully!\n";
