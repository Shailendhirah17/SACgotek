<?php
/**
 * ERP Final Production Sync & Menu Seeding
 * Run: php artisan tinker final_production_sync.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

$modules = [
    'Zoom', 'OnlineExam', 'ParentRegistration', 'RazorPay', 'BBB', 'Jitsi', 
    'Saas', 'XenditPayment', 'AppSlider', 'KhaltiPayment', 'Raudhahpay', 
    'InfixBiometrics', 'Gmeet', 'PhonePay', 'Lms', 'CcAveune', 'AiContent', 
    'WhatsappSupport', 'Certificate', 'InAppLiveClass', 'MercadoPago', 
    'University', 'ToyyibPay', 'News', 'PDF', 'SaasHr', 'Forum', 
    'CustomMenu', 'QRCodeAttendance', 'SslCommerz', 'DashboardAnalytics', 
    'AdvancedStudentPortal', 'AdvancedHrPortal', 'AdvancedExamPortal', 
    'SmartTransport', 'SmartCommunication', 'SmartFrontOffice'
];

echo "🏁 Starting Final Production Sync...\n";

// 1. Update modules_statuses.json
$jsonPath = base_path('modules_statuses.json');
if (File::exists($jsonPath)) {
    $statuses = json_decode(File::get($jsonPath), true);
    foreach ($modules as $name) {
        $statuses[$name] = true;
    }
    File::put($jsonPath, json_encode($statuses, JSON_PRETTY_PRINT));
    echo "✅ modules_statuses.json updated.\n";
}

// 2. Seed sm_menus for Custom Modules
$customMenus = [
    ['name' => 'Dashboard Analytics', 'module' => 'DashboardAnalytics', 'route' => 'dashboardanalytics.index', 'icon' => 'flaticon-analytics'],
    ['name' => 'Advanced Student Portal', 'module' => 'AdvancedStudentPortal', 'route' => 'advancedstudentportal.index', 'icon' => 'flaticon-student'],
    ['name' => 'Advanced HR Portal', 'module' => 'AdvancedHrPortal', 'route' => 'advancedhrportal.index', 'icon' => 'flaticon-staff'],
    ['name' => 'Advanced Exam Portal', 'module' => 'AdvancedExamPortal', 'route' => 'advancedexamportal.index', 'icon' => 'flaticon-exam'],
    ['name' => 'Smart Transport', 'module' => 'SmartTransport', 'route' => 'smarttransport.index', 'icon' => 'flaticon-bus'],
    ['name' => 'Smart Communication', 'module' => 'SmartCommunication', 'route' => 'smartcommunication.index', 'icon' => 'flaticon-chat'],
    ['name' => 'Smart Front Office', 'module' => 'SmartFrontOffice', 'route' => 'smartfrontoffice.index', 'icon' => 'flaticon-visitor'],
];

foreach ($customMenus as $menu) {
    DB::table('sm_menus')->updateOrInsert(
        ['name' => $menu['name'], 'module' => $menu['module']],
        [
            'route' => $menu['route'],
            'icon' => $menu['icon'],
            'status' => 1,
            'menu_status' => 1,
            'school_id' => 1,
            'is_saas' => 0,
            'parent_id' => 0,
            'section_id' => 1, // Basic Administrative section
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
    echo "✅ Menu registered: " . $menu['name'] . "\n";
}

// 3. Clear all caches again
Cache::flush();
Artisan::call('cache:clear');
Artisan::call('view:clear');
Artisan::call('config:clear');

echo "\n✨ ALL DONE! Refresh your sidebar on production.\n";
