<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolsClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',

    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function divisions()
    {
        return $this->hasMany(SchoolsClassesDivision::class,'school_class_id');
    }

    public function classsModel()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }
}
