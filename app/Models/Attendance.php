<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances'; // اسم الجدول في قاعدة البيانات

    protected $fillable = [
        'student_id',
        'attendance_array',
    ];

    protected $casts = [
        'attendance_array' => 'array', // تحديد نوع الحقل كمصفوفة
    ];

    // ارتباط الطالب بالحضور
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
