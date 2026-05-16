<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\SmStudent;
use App\User;

$student = SmStudent::where('admission_no', 'TEST0001')->first();
if ($student) {
    echo "Student ID: " . $student->id . " - Role ID: " . $student->role_id . "\n";
    $user = User::find($student->user_id);
    if ($user) {
        echo "User ID: " . $user->id . " - Role ID: " . $user->role_id . "\n";
    }
} else {
    echo "Student not found\n";
}
