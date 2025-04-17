<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'description',
        'file',
        
    ];

    public function schoolClassDivision()
    {
        return $this->belongsTo(SchoolsClassesDivision::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
