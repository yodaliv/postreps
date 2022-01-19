<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessory_id',
        'name',
        "user_id",
    ];


    public function accessory()
    {
        $this->belongsTo(Accessory::class, 'id', 'accessory_id');
    }
}
