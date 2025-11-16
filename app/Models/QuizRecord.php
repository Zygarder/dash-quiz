<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizRecord extends Model
{
    use HasFactory;
    public $table = 'quiz_records';
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'completed_at',
    ];
}
