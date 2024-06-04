<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    protected $fillable = ['content', 'user_id', 'subject_id', 'unit_id'];

public function user()
{
    return $this->belongsTo(User::class);
}

public function subject()
{
    return $this->belongsTo(Subject::class);
}

public function unit()
{
    return $this->belongsTo(Unit::class);
}
}