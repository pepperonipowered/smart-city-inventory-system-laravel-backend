<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Report;

class Source extends Model
{
    //
    protected $table = 'source';

    protected $fillable = [
        'sources',
        'is_deleted'
    ];

    public function reports(){
        return $this->hasMany(Report::class, 'source_id');
    }
}
