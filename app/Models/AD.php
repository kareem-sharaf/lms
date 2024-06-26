<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AD extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'description',
        'image_url',
        'year_id',
        'stage_id',
        'isExpired'
    ];
    public function videos()
{
    return $this->hasMany(Video::class);
}
}
