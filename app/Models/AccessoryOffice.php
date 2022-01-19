<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessory_id',
        'office_id',
        'price',
        'access',
        'locked'
    ];
}
