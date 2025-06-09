<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfAssistance extends Model
{
    //
    protected $table = 'type_of_assistance';

    protected $fillable = [
        'assistance',
        'is_deleted'
    ];

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'assistance_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'assistance_id');
    }
}
