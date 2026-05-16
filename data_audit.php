<?php
// data_audit.php - Raw SQL Audit

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: application/json');

$students = DB::table('sm_students')
    ->select('id', 'full_name', 'admission_no', 'school_id', 'academic_id', 'class_id', 'section_id', 'active_status')
    ->get();

$classes = DB::table('sm_classes')
    ->select('id', 'class_name', 'school_id')
    ->get();

$sections = DB::table('sm_sections')
    ->select('id', 'section_name', 'school_id')
    ->get();

echo json_encode([
    'students' => $students,
    'classes' => $classes,
    'sections' => $sections
], JSON_PRETTY_PRINT);
