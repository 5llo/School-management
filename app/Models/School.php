<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'bus_price',
        'email',
        'password',
        'location'
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolsClass::class);
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }


}
