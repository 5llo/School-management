<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = [

        'name'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolsClassesDivisions()
    {
        return $this->hasMany(SchoolsClassesDivision::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolsClass::class);
    }
}
