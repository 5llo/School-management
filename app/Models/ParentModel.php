<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'parents';

    // Specify the fillable fields
    protected $fillable = [
        'name',
        'email',
        'password',
        'location',
        'phone',
    ];

    // Optionally, you can add hidden fields (like password) for security
    protected $hidden = [
        'password',
    ];
}
