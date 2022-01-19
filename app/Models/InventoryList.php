<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryList extends Model
{
    use HasFactory;

    protected $fillable = [
        "accessory_id",
        "item_id",
        "office_id",
        "agent_id"
    ];


    public function accessory()
    {
        $this->hasOne(Accessory::class, 'id', 'accessory_id');
    }

    public function office()
    {
        $this->hasOne(Office::class, 'id', 'accessory_id');
    }

    public function agent()
    {
        $this->hasOne(Agent::class, 'id', 'agent_id');
    }
}
