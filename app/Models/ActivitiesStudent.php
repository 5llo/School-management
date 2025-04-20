<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitiesStudent extends Model
{
    use HasFactory;
    protected $table = 'activities_students';

    protected $fillable = [
        'activity_id',
        'student_id',
        'process_number',
        'status',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
