<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use Laravel\Sanctum\HasApiTokens;
class School extends Model
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'name',
        'description',
        'bus_price',
        'email',
        'password',
        'location'
    ];
    public $timestamps = false;
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
