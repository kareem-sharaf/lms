<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonQuiz extends Model
{
    use HasFactory;
    protected $fillable=[
        'lesson_id',
        'quiz_id'
    ];


    public function quizes()
    {
        return $this->hasMany(Quiz::class);
    }
}
