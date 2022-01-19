<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'office_id',
        'price',
        'access',
        'locked'
    ];
}
