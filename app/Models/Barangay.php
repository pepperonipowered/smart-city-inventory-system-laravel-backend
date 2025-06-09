<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    //
    protected $table = 'barangay';

    protected $fillable = [
        'name',
        'longitude',
        'latitude',
        'is_deleted'
    ];

    public function reports(){
        return $this->hasMany(Report::class, 'barangay_id');
    }
}
