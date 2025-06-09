<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoadType extends Model
{
    use HasFactory;

    protected $table = 'road_type';

    protected $fillable = [
        'type_name',
    ];

    public function roads()
    {
        return $this->hasMany(Road::class);
    }
}
