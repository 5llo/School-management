<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusDriver extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'driver_id',
        'plate_number',
        'capacity',
        'route_description'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function driver()
    {
        return $this->belongsTo(BusDriver::class, 'driver_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'bus_id');
    }
}
