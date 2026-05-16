<?php

namespace App\Events\SuperAdmin;

use App\Models\SuperAdmin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuperAdminUserStatusChanged
{
    use Dispatchable, SerializesModels;

    public $superAdmin;
    public $targetUser;
    public $oldStatus;
    public $newStatus;

    public function __construct(SuperAdmin $superAdmin, SuperAdmin $targetUser, bool $oldStatus, bool $newStatus)
    {
        $this->superAdmin = $superAdmin;
        $this->targetUser = $targetUser;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
