<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        "price",
        "free_storage",
        "cost_per_unit",
        "frequency"
    ];

    const DEFAULT_SETTING = [
        "price" => 150,
        "free_storage" => 25,
        "cost_per_unit" => 50,
        "frequency" => 90
    ];
}
