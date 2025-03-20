<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolsClassesDivisionActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'school_classes_division_id'
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function schoolClassesDivision()
    {
        return $this->belongsTo(SchoolsClassesDivision::class);
    }

}
