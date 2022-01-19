<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;

    protected $fillable =  [

            "panel_name",
            "quantity",
            "price",
            "free_storage",
            "cost_per_unit",
            "frequency",
            "width",
            "height",
            "office_id",
            "agent_id",
            "status",
            "image_path",
            "item_id_number",
            "item_id_code",
            "listing_order"

    ];


    public function agents()
    {
        return $this->hasMany(PanelAgent::class ,'panel_id','id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
