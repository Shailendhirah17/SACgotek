<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

try {
    $genders = DB::table('sm_base_setups')->where('base_group_id', 1)->get();
    echo "<pre>";
    echo "Total genders found: " . count($genders) . "\n";
    foreach ($genders as $g) {
        echo "- ID: {$g->id}, Name: {$g->base_setup_name}, School: {$g->school_id}\n";
    }
    echo "</pre>";
} catch (Exception $e) {
    echo $e->getMessage();
}
