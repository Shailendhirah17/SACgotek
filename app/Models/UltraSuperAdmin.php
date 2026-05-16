<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

/**
 * Ultra Super Admin Model
 *
 * Master control layer owned by Technosprint Info Solutions.
 * Has ultimate authority over all organizations, school groups,
 * subscriptions, and feature toggles.
 *
 * Hierarchy: Ultra Super Admin → Super Admin → Admin
 */
class UltraSuperAdmin extends Authenticatable
{
    // use HasApiTokens, HasFactory, Notifiable;
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ultra_super_admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
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
     * Get school groups created by this Ultra Super Admin.
     */
    public function schoolGroups()
    {
        return $this->hasMany(SchoolGroup::class, 'created_by');
    }

    /**
     * Get the creator of this Ultra Super Admin.
     */
    public function creator()
    {
        return $this->belongsTo(UltraSuperAdmin::class, 'created_by');
    }

    /**
     * Get the last updater of this Ultra Super Admin.
     */
    public function updater()
    {
        return $this->belongsTo(UltraSuperAdmin::class, 'updated_by');
    }

    /**
     * Check if this user is an Ultra Super Admin.
     */
    public function isUltraSuperAdmin(): bool
    {
        return $this->role === 'ultra_super_admin';
    }

    /**
     * Check if the account is active.
     */
    public function isActive(): bool
    {
        return $this->active_status === true;
    }

    /**
     * Scope a query to only include active Ultra Super Admins.
     */
    public function scopeActive($query)
    {
        return $query->where('active_status', true);
    }

    /**
     * Scope a query to only include inactive Ultra Super Admins.
     */
    public function scopeInactive($query)
    {
        return $query->where('active_status', false);
    }
}
