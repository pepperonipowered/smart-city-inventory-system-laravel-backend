<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoadType;
use App\Models\Inbound;
use App\Models\Outbound;

class Road extends Model
{
    protected $table = 'roads';

    protected $fillable = [
        'road_name',
        'road_type_id',
        'image_path',
        'is_deleted',
    ];

    public function roadType()
    {
        return $this->belongsTo(RoadType::class, 'road_type_id');
    }

    public function inbound()
    {
        return $this->hasMany(Inbound::class);
    }

    public function outbound()
    {
        return $this->hasMany(Outbound::class);
    }

    public function statusTraffic()
    {
        return $this->hasMany(StatusTraffic::class);
    }
}
