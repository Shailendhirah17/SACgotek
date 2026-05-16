<?php

namespace Modules\RolePermission\Entities;
use Illuminate\Database\Eloquent\Model;

class InfixRole extends Model
{
    protected $fillable = [];
    protected $casts = [
        'saas_schools' => 'array',
        'id' => 'integer',
        'name' => 'string',
    ];  

    public function getNameAttribute($value)
    {
        if (auth()->check() && auth()->user()->school_id > 1 && $this->id == 1) {
            return 'Admin';
        }
        return $value;
    }

    public function assignedPermission()
    {
        return $this->hasMany(AssignPermission::class, 'role_id', 'id')->where('school_id', auth()->user()->school_id);
    }

    public function saasAssignments()
    {
        return $this->hasMany(AssignPermission::class, 'role_id', 'id');
    }
}
