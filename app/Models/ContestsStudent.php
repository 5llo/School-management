<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestsStudent extends Model
{
    use HasFactory;

    protected $table = 'contests_students';

    protected $fillable = [
        'student_id',
        'contest_id',
        'result'
    ];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
