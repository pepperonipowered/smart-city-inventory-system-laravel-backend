<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    //
    protected $table = 'incident';

    protected $fillable = [
        'type',
        'assistance_id',
        'is_deleted'
    ];

    public function typeOfAssistance()
    {
        return $this->belongsTo(TypeOfAssistance::class, 'assistance_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'incident_id');
    }
}
