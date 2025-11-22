<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // important
use Illuminate\Notifications\Notifiable;

class Dasher extends Authenticatable
{
    use Notifiable;

    protected $table = 'dasher'; 

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
