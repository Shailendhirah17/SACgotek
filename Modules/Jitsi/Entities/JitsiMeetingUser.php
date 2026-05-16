<?php

namespace Modules\Jitsi\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JitsiMeetingUser extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Jitsi\Database\factories\JitsiMeetingUserFactory::new();
    }
}
