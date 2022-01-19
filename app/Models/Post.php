<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "post_name",
        "quantity",
        "price",
        "renewal_fee",
        "loss_damage",
        "time_days",
        "point_value",
        "office_id",
        "agent_id",
        "default",
        "status",
        "image_path",
        "item_id_number",
        "item_id_code",
        "listing_order"
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    public function office_access()
    {
        return $this->hasMany(PostOffice::class, 'post_id', 'id')->where('access', 1);
    }

    public function agent_access()
    {
        return $this->hasMany(PostAgent::class, 'post_id', 'id')->where('access', 1);
    }

    public function post_offices()
    {
        return $this->hasMany(PostOffice::class);
    }

    public function post_agents()
    {
        return $this->hasMany(PostAgent::class);
    }
}
