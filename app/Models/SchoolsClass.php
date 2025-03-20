<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolsClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'description'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function divisions()
    {
        return $this->hasMany(SchoolsClassesDivision::class);
    }
}
