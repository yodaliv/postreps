<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        "color",
        "points",
        "size",
        "name",
        "zone_fee",
        "installer_points",
        "su",
        "m",
        "tu",
        "w",
        "th",
        "f",
        "sa"
    ];
}
