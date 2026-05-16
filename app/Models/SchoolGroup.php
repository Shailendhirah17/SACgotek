<?php

namespace App\Models;

use App\SmSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * School Group Model
 *
 * Organizational container managed by Ultra Super Admin.
 * Each group contains multiple schools and has its own
 * subscription plan, feature toggles, and billing info.
 *
 * Hierarchy: Ultra Super Admin → School Group → Schools → Super Admin
 */
class SchoolGroup extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'active_status',
        'subscription_plan',
        'subscription_start',
        'subscription_end',
        'max_schools',
        'max_students_per_school',
        'license_key',
        'features_config',
        'billing_contact_name',
        'billing_contact_email',
        'billing_address',
        'billing_phone',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active_status' => 'boolean',
        'features_config' => 'array',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
    ];

    /**
     * Get all schools in this group.
     */
    public function schools()
    {
        return $this->hasMany(SmSchool::class, 'school_group_id');
    }

    /**
     * Get all feature toggles for this group.
     */
    public function features()
    {
        return $this->hasMany(SchoolGroupFeature::class, 'school_group_id');
    }

    /**
     * Get the creator (Ultra Super Admin).
     */
    public function creator()
    {
        return $this->belongsTo(UltraSuperAdmin::class, 'created_by');
    }

    /**
     * Check if the subscription is currently active.
     */
    public function isSubscriptionActive(): bool
    {
        if (!$this->subscription_end) {
            return true; // No end date means unlimited
        }
        return $this->subscription_end->isFuture();
    }

    /**
     * Get the number of active schools in this group.
     */
    public function activeSchoolsCount(): int
    {
        return $this->schools()->where('active_status', 1)->count();
    }

    /**
     * Check if the group can add more schools.
     */
    public function canAddSchool(): bool
    {
        return $this->schools()->count() < $this->max_schools;
    }

    /**
     * Enable a feature for this group.
     */
    public function enableFeature(string $featureKey, string $featureName = '', array $config = []): SchoolGroupFeature
    {
        return $this->features()->updateOrCreate(
            ['feature_key' => $featureKey],
            [
                'feature_name' => $featureName ?: $featureKey,
                'is_enabled' => true,
                'config' => $config,
            ]
        );
    }

    /**
     * Disable a feature for this group.
     */
    public function disableFeature(string $featureKey): bool
    {
        return $this->features()
            ->where('feature_key', $featureKey)
            ->update(['is_enabled' => false]) > 0;
    }

    /**
     * Check if a feature is enabled for this group.
     */
    public function isFeatureEnabled(string $featureKey): bool
    {
        $feature = $this->features()->where('feature_key', $featureKey)->first();
        return $feature ? $feature->is_enabled : false;
    }

    /**
     * Get all enabled features for this group.
     */
    public function getEnabledFeatures()
    {
        return $this->features()->where('is_enabled', true)->get();
    }

    /**
     * Scope: active groups only.
     */
    public function scopeActive($query)
    {
        return $query->where('active_status', true);
    }

    /**
     * Scope: groups with active subscriptions.
     */
    public function scopeWithActiveSubscription($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('subscription_end')
              ->orWhere('subscription_end', '>=', now());
        });
    }

    /**
     * Get subscription status label.
     */
    public function getSubscriptionStatusAttribute(): string
    {
        if (!$this->subscription_end) {
            return 'Unlimited';
        }
        if ($this->subscription_end->isPast()) {
            return 'Expired';
        }
        if ($this->subscription_end->diffInDays(now()) <= 30) {
            return 'Expiring Soon';
        }
        return 'Active';
    }
}
