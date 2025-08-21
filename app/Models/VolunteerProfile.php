<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerProfile extends Model
{
    use HasFactory;

    protected $table = 'volunteers';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'date_of_birth',
        'skills',
        'interests',
        'license_number',
        // keep kyc_status out of mass-assign unless you want user-editable
    ];

    protected $dates = ['date_of_birth'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
