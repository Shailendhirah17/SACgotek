<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SchoolCreatedBySuperAdmin
{
    use Dispatchable, SerializesModels;

    public $superAdmin;
    public $school;

    public function __construct(SuperAdmin $superAdmin, $school)
    {
        $this->superAdmin = $superAdmin;
        $this->school = $school;
    }
}
