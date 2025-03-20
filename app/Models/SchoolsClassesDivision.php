<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolsClassesDivision extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'division_id',
        'name'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolsClass::class, 'school_class_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'schools_classes_division_activities');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'schools_classes_division_posts');
    }

    
}
