<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuperAdminUserCreated
{
    use Dispatchable, SerializesModels;

    public $superAdmin;
    public $createdUser;

    public function __construct(SuperAdmin $superAdmin, SuperAdmin $createdUser)
    {
        $this->superAdmin = $superAdmin;
        $this->createdUser = $createdUser;
    }
}
