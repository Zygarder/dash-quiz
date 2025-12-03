<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{

    public $table = 'admin';

    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
