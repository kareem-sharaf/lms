<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image_data',
        'price',
        'video_id',
        'file_id',
        'category_id'
    ];
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // public $timestamps=false;
    public function users()
    {
        return $this->belongsToMany(User::class,'subscriptions');
    }
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function years_users()
    {
        return $this->belongsToMany(User::class, 'teacher_subject_years', 'subject_id', 'user_id')
                    ->withPivot('year_id');
    }

    


}
