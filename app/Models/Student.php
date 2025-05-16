<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;

class Student extends Model
{
    use HasFactory;
    Protected $table ="students";
    protected $fillable = [
        'parent_id',
        'bus_driver_id',
        'school_class_division_id',
        'name'
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }
    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    public function schoolClassDivision()
    {
        return $this->belongsTo(SchoolsClassesDivision::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activities_students');
    }

    public function busDriver()
    {
        return $this->belongsTo(BusDriver::class, 'bus_driver_id');
    }

    public function schoolsFoodMeals()
    {
        return $this->belongsToMany(FoodMeal::class, 'students_schools_food_meals');
    }

    public function contests()
    {
        return $this->belongsToMany(Contest::class, 'contests_students');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects')->withPivot([
            'session_id',
         //   'attendance_array',
            'oral_grade',
            'homework_grade',
           'exam_grade'
        ]);;
    }

    /*
    public function subjects()
{
    return $this->belongsToMany(Subject::class, 'student_subjects')->withPivot([
        'session_id',
        'attendance_array',
        'oral_grade',
        'homework_grade',
        'exam_grade'
    ]);
}*/

    public function conversations()
    {
        return $this->morphMany(ConversationParticipant::class, 'user');
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

     
}
