<?php

use Illuminate\Support\Facades\DB;

try {
    $all = DB::table('permissions')->select('id', 'name', 'lang_name', 'route', 'module')->whereNull('parent_route')->limit(10)->get();
    echo "Sample permissions:\n";
    foreach ($all as $p) {
        echo "- ID: {$p->id}, Name: {$p->name}, Lang: '{$p->lang_name}'\n";
    }

    $wa = DB::table('permissions')->where('name', 'like', '%Whatsapp%')->first();
    if ($wa) {
        echo "Whatsapp: Name: {$wa->name}, Lang: '{$wa->lang_name}'\n";
    }

    echo "\nTotal permissions without parent: " . DB::table('permissions')->whereNull('parent_route')->count() . "\n";
    
    // permissions with empty lang_name
    $emptyLang = DB::table('permissions')->whereNull('parent_route')
        ->where(function($q) {
            $q->whereNull('lang_name')->orWhere('lang_name', '');
        })->count();
    
    echo "Permissions with empty lang_name: " . $emptyLang . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
