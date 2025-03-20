<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'title',
        'body',
        'read_at'
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }
    
}
