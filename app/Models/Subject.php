<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',

    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects')->withPivot([
            'session_id',
            'attendance_array',
            'oral_grade',
            'homework_grade',
            'exam_grade'
        ]);
    }
}
