<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'primary_contact',
        'website',
        'inactive',
        'private',
        'name_abbreviation',
        'logo_image',
        'payment_method',
        'note',
    ];

    // Payment methods
    const PAYMENT_METHOD_PAY_AT_TIME_OF_ORDER = 1;
    const PAYMENT_METHOD_INVOICE = 2;
    const PAYMENT_METHOD_DEFAULT = self::PAYMENT_METHOD_PAY_AT_TIME_OF_ORDER;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function agents()
    {
        return $this->hasMany(Agent::class, 'agent_office', 'id');
    }

    public function active_agents()
    {
        return $this->hasMany(Agent::class, 'agent_office', 'id')->where('inactive',0);
    }
}
