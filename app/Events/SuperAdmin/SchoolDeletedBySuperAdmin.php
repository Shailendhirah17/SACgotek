<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SchoolDeletedBySuperAdmin
{
    use Dispatchable, SerializesModels;

    public $superAdmin;
    public $schoolData;

    public function __construct(SuperAdmin $superAdmin, array $schoolData)
    {
        $this->superAdmin = $superAdmin;
        $this->schoolData = $schoolData;
    }
}
