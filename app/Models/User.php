<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'verified',
        'email_sent_at',
        'password',
        'device_id',
        'verificationCode',
        'birth_date',
        'gender',
        'verified',
        'address_id',
        'role_id',
        'stage_id',
        'year_id',
        'image_id'
    ];

    public $timestamps = false;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function courses()
    // {
    //     return $this->hasMany(Course::class);
    // }

    public function favorites()
    {
        return $this->morphToMany(Favorite::class, 'favoritable');
    }

    public function favoritess()
    {
        return $this->belongsToMany(Favorite::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject_years', 'user_id', 'subject_id');
    }

    public function subjects_users()
    {
        return $this->belongsToMany(User::class, 'teacher_subject_years', 'subject_id', 'user_id')
                    ->withPivot('subject_id');
    }
}
