<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuperAdminLoggedOut
{
    use Dispatchable, SerializesModels;

    public $superAdmin;

    public function __construct(SuperAdmin $superAdmin)
    {
        $this->superAdmin = $superAdmin;
    }
}
