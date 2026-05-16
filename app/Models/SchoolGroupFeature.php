<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * School Group Feature Model
 *
 * Granular feature toggles per school group.
 * Ultra Super Admin can enable/disable individual features for each group.
 */
class SchoolGroupFeature extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_group_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_group_id',
        'feature_key',
        'feature_name',
        'is_enabled',
        'config',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'config' => 'array',
    ];

    /**
     * Get the school group that this feature belongs to.
     */
    public function schoolGroup()
    {
        return $this->belongsTo(SchoolGroup::class, 'school_group_id');
    }
}
