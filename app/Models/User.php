<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'state',
        'zipcode',
        'region_id',
        'email_verified_at',
        'customer_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ROLE_SUPER_ADMIN = 1;
    const ROLE_OFFICE = 2;
    const ROLE_AGENT = 3;
    const ROLE_INSTALLER = 4;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'user_id', 'id');
    }
    public function office()
    {
        return $this->hasOne(Office::class, 'user_id', 'id');
    }

    public function lastNameFirstName()
    {
        return $this->lastNameFirstName = $this->last_name . ", " . $this->first_name;
    }

    public function authorizenet_payment_profiles()
    {
        return $this->hasMany(AuthorizenetPaymentProfile::class);
    }

}
