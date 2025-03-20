<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolsClassesDivisionPost extends Model
{
    use HasFactory;

    protected $table = 'schools_classes_division_posts';

    protected $fillable = [
        'post_id',
        'school_classes_division_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function schoolClassesDivision()
    {
        return $this->belongsTo(SchoolsClassesDivision::class);
    }
}
