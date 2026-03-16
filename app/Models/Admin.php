<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $table = 'admin';

    protected $fillable = ['first_name', 'last_name','role', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
