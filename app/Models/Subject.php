<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image_data',
        'stage_id'
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function years()
    {
        return $this->belongsToMany(Year::class,'subject_year');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject');
    }
}
