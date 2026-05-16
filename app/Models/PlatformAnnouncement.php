<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'platform_announcements';

    protected $fillable = [
        'title',
        'message',
        'priority',
        'target_school_group_id',
        'is_published',
        'created_by',
    ];

    /**
     * Get the school group this announcement is targeting (if any).
     */
    public function targetGroup()
    {
        return $this->belongsTo(SchoolGroup::class, 'target_school_group_id');
    }

    /**
     * Get the authoritative creator of the announcement.
     */
    public function creator()
    {
        return $this->belongsTo(UltraSuperAdmin::class, 'created_by');
    }
}
