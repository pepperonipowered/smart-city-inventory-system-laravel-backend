<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Road;
use App\Models\StatusTraffic;

class Inbound extends Model
{
    protected $table = 'inbound';

    protected $fillable = [
        'road_id',
        'status_traffic_id',
    ];

    public function road()
    {
        return $this->belongsTo(Road::class);
    }

    public function statusTraffic()
    {
        return $this->belongsTo(StatusTraffic::class);
    }


}
