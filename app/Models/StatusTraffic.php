<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusTraffic extends Model
{
    protected $table = 'status_traffic';

    protected $fillable = [
        'status_name',
    ];
}
