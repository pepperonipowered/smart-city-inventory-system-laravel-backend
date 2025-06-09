<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urgency extends Model
{
    //
    protected $table = 'urgency';

    protected $fillable = [
        'urgency',
        'description',
        'is_deleted'
    ];

    public function reports(){
        return $this->hasMany(Report::class, 'urgency_id');
    }
}
