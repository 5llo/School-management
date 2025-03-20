<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_name',
        'start_date',
        'end_date',
    ];

    // علاقات ممكنة حسب بقية الجداول
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
