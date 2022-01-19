<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        "install",
        "repair",
        "removal",
        "delivery",
        'rush_order'
    ];



    const DEFAULT_SETTING = [
        "install" => 100,
        "repair" => 50,
        "removal" => 0,
        "delivery" => 50,
        "rush_order" => 0
    ];
}
