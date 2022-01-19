<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAccessory extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'accessory_id'];

    public function accessory()
    {
        return $this->belongsTo(Accessory::class,'accessory_id','id');
    }
}
