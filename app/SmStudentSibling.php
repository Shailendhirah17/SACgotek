<?php

namespace App;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;

class SmStudentSibling extends Model
{
    protected $guarded = ['id'];

    public function student()
    {
        return $this->belongsTo(SmStudent::class, 'student_id', 'id');
    }

    public function sibling()
    {
        return $this->belongsTo(SmStudent::class, 'sibling_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(SmClass::class, 'class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(SmSection::class, 'section_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
}
