<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SaasTicketReply extends Model
{
    protected $table = 'saas_ticket_replies';
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo(SaasTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function superAdmin()
    {
        return $this->belongsTo(SuperAdmin::class, 'super_admin_id');
    }
}
