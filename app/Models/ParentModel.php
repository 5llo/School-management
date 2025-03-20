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
        'phone'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function conversations()
    {
        return $this->morphMany(ConversationParticipant::class, 'user');
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
