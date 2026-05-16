<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\SmSchool;

class SaasTicket extends Model
{
    protected $table = 'saas_tickets';
    protected $guarded = ['id'];

    public function school()
    {
        return $this->belongsTo(SmSchool::class, 'school_id');
    }

    public function category()
    {
        return $this->belongsTo(SaasTicketCategory::class, 'category_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(SuperAdmin::class, 'super_admin_id');
    }

    public function replies()
    {
        return $this->hasMany(SaasTicketReply::class, 'ticket_id')->orderBy('created_at', 'asc');
    }
}
