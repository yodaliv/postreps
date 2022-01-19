<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessory_id',
        'agent_id',
        'price',
        'access',
        'locked'
    ];
}
