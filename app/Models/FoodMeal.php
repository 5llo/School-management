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
        'description',
        'price',
        'day'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'students_schools_food_meals');
    }
}
