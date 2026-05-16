<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuperAdminLoggedIn
{
    use Dispatchable, SerializesModels;

    public $superAdmin;
    public $ipAddress;

    public function __construct(SuperAdmin $superAdmin, string $ipAddress)
    {
        $this->superAdmin = $superAdmin;
        $this->ipAddress = $ipAddress;
    }
}
