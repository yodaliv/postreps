<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        "accessory_name",
        "quantity",
        "price",
        "loss_damage",
        "point_value",
        "prompt",
        "message",
        "inventory",
        "pull_list",
        "default",
        "status",
        'image',
        'item_id_number',
        'item_id_code',
        "document_upload_prompt_heading",
        "document_upload_prompt_message",
        "popup_message_heading",
        "popup_message_message",
        "listing_order"
    ];


    public function inventories()
    {
        return $this->hasMany(InventoryList::class, 'accessory_id', 'id')->orderBy("item_id", 'asc');
    }

    public function office_access()
    {
        return $this->hasMany(AccessoryOffice::class, 'accessory_id', 'id')->where('access', 1);
    }
    public function agent_access()
    {
        return $this->hasMany(AccessoryAgent::class, 'accessory_id', 'id')->where('access', 1);
    }

    public function accessory_offices()
    {
        return $this->hasMany(AccessoryOffice::class);
    }

    public function accessory_agents()
    {
        return $this->hasMany(AccessoryAgent::class);
    }
}
