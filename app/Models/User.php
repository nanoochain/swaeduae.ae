<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;



use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;
    protected string $guard_name = 'web';

    protected $fillable = [
        'name','email','password',
        'phone','nationality','gender','dob','emirate','city',
        'passport_no','emirates_id',
        'education','experience','languages','skills',
        'interests','availability','bio','photo_path','tos_accepted_at',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
        'tos_accepted_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function volunteerHours()
    {
        return $this->hasMany(\App\Models\VolunteerHour::class);
    }
}

