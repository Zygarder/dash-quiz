<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'admin_log';

    protected $fillable = ['admin_id', 'action_type', 'description'];

}
