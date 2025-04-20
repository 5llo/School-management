<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date_start',
        'duration',
        'teacher_id'
    ];

   
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }


    public function students()
    {
        return $this->belongsToMany(Student::class, 'contests_students');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

}
