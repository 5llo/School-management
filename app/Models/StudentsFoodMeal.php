<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsFoodMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'food_meal_id',
        'date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function foodMeal()
    {
        return $this->belongsTo(FoodMeal::class);
    }
}
