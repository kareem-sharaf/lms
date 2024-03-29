<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectYear extends Model
{
    protected $fillable=[
        'teacher_id',
        'subject_year_id'
    ];
    use HasFactory;
}
