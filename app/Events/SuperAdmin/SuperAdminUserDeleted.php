<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuperAdminUserDeleted
{
    use Dispatchable, SerializesModels;

    public $superAdmin;
    public $deletedUserData;

    public function __construct(SuperAdmin $superAdmin, array $deletedUserData)
    {
        $this->superAdmin = $superAdmin;
        $this->deletedUserData = $deletedUserData;
    }
}
