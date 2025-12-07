<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // important
use Illuminate\Notifications\Notifiable;

class Dasher extends Authenticatable
{
    use Notifiable;

    protected $table = 'dasher';

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'profile_photo',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function get_profile()
    {
        return $this->profile_photo
            ? asset('storage/images/profiles/' . $this->profile_photo)
            : asset('images/profiles/person.jpg'); // fallback
    }
}
