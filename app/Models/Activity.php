<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'date',
        'time',
        'start',
        'phone'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'activities_students');
    }

    public function schoolsClassesDivisions()
    {
        return $this->belongsToMany(SchoolsClassesDivision::class, 'schools_classes_division_activities');
    }
}
