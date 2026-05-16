<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SuperAdmin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'super_admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'school_group_id',

        'email',
        'password',
        'full_name',
        'phone_number',
        'active_status',
        'role',
        'created_by',
        'updated_by',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active_status' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get audit logs for this SuperAdmin.
     */
    public function auditLogs()
    {
        return $this->hasMany(SuperAdminAuditLog::class, 'super_admin_id');
    }

    /**
     * Get the assigned school group for this Super Admin.
     */
    public function schoolGroup()
    {
        return $this->belongsTo(SchoolGroup::class, 'school_group_id');
    }

    /**
     * Get the creator of this SuperAdmin.
     */
    public function creator()
    {
        return $this->belongsTo(SuperAdmin::class, 'created_by');
    }

    /**
     * Get the last updater of this SuperAdmin.
     */
    public function updater()
    {
        return $this->belongsTo(SuperAdmin::class, 'updated_by');
    }

    /**
     * Check if this SuperAdmin has the super_admin role.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the account is active.
     */
    public function isActive(): bool
    {
        return $this->active_status === true;
    }

    /**
     * Scope a query to only include active SuperAdmins.
     */
    public function scopeActive($query)
    {
        return $query->where('active_status', true);
    }

    /**
     * Scope a query to only include inactive SuperAdmins.
     */
    public function scopeInactive($query)
    {
        return $query->where('active_status', false);
    }
}
