<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuperAdminUserUpdated
{
    use Dispatchable, SerializesModels;

    public $superAdmin;
    public $updatedUser;
    public $oldValues;
    public $newValues;

    public function __construct(SuperAdmin $superAdmin, SuperAdmin $updatedUser, array $oldValues = [], array $newValues = [])
    {
        $this->superAdmin = $superAdmin;
        $this->updatedUser = $updatedUser;
        $this->oldValues = $oldValues;
        $this->newValues = $newValues;
    }
}
