<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // important
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dasher extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'dasher';

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'role',
        'profile_photo',
        'password',
        'last_activity',
        'active_status',
    ];

    protected $hidden = [
        'active',
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return "http://127.0.0.1:8000/reset?token={$token}&email={$user->email}";
        });
    }

    public function get_profile()
    {
        return $this->profile_photo
            ? asset('storage/images/profiles/' . $this->profile_photo)
            : asset('images/profiles/default.jpg'); // fallback if no image
    }

    public function quizRecords()
    {
        return $this->hasMany(QuizRecord::class, 'user_id');
    }
}
