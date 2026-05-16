<?php
// seed_infrastructure.php - Phase A: Classes and Sections

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\SmClass;
use App\SmSection;
use App\SmClassSection;
use Illuminate\Support\Facades\DB;

$school_id = 1;
$academic_id = 1;

echo "Starting Phase A: Infrastructure Seeding...\n";

DB::beginTransaction();
try {
    // 1. Ensure Sections A, B, C exist
    $section_names = ['A', 'B', 'C'];
    $sections = [];
    foreach ($section_names as $name) {
        $section = SmSection::where('section_name', $name)
            ->where('school_id', $school_id)
            ->first();
        if (!$section) {
            $section = new SmSection();
            $section->section_name = $name;
            $section->school_id = $school_id;
            $section->academic_id = $academic_id;
            $section->active_status = 1;
            $section->save();
        }
        $sections[$name] = $section->id;
        echo "Section $name: ID {$section->id}\n";
    }

    // 2. Create 12 Classes
    for ($i = 1; $i <= 12; $i++) {
        $className = "Class $i";
        $class = SmClass::where('class_name', $className)
            ->where('school_id', $school_id)
            ->first();
        if (!$class) {
            $class = new SmClass();
            $class->class_name = $className;
            $class->school_id = $school_id;
            $class->academic_id = $academic_id;
            $class->active_status = 1;
            $class->save();
            echo "Created Class: $className (ID: {$class->id})\n";
        } else {
            echo "Class $className already exists (ID: {$class->id})\n";
        }

        // 3. Map A, B, C to this Class
        foreach ($sections as $name => $sectionId) {
            $exists = SmClassSection::where('class_id', $class->id)
                ->where('section_id', $sectionId)
                ->where('school_id', $school_id)
                ->first();
            if (!$exists) {
                $cs = new SmClassSection();
                $cs->class_id = $class->id;
                $cs->section_id = $sectionId;
                $cs->school_id = $school_id;
                $cs->academic_id = $academic_id;
                $cs->save();
            }
        }
    }

    DB::commit();
    echo "Phase A Completed Successfully!\n";
} catch (\Exception $e) {
    DB::rollback();
    echo "Phase A Failed: " . $e->getMessage() . "\n";
}
