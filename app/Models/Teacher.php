<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Model
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'school_id',
        'schools_classes_division_id',
        'phone',
        'gender',
        'email',
        'password',
        'name',
        'fcmtoken'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }


    public function division()
    {
        return $this->belongsTo(SchoolsClassesDivision::class, 'schools_classes_division_id');
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    // public function posts()
    // {
    //     return $this->hasMany(Post::class);
    // }



    public function contests()
    {
        return $this->hasMany(Contest::class);
    }
}
