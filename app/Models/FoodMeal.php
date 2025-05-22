<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'contents',
        'price',
        'entrees',
        'day',
        'Food'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class,  'students_food_meals', 'food_meal_id', 'student_id');
    }
}
