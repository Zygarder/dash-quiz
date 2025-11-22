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

    public $timestamps = false;

    protected $dates = ['completed_at'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function user()
    {
        return $this->belongsTo(Dasher::class, 'user_id');
    }

}
