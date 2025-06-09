<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Report;

class ActionsTaken extends Model
{
    //
    protected $table = 'actions_taken';
    
    protected $fillable = [
        'action',
        'is_deleted'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'actions_id');
    }
}
