<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dasher extends Model
{
    use HasFactory;

    public $table = 'dasher';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];


}
