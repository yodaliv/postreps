<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelAgent extends Model
{
    use HasFactory;

    protected $fillable =[
        "panel_id",
        'agent_id',
        'is_primary'
    ];
}
