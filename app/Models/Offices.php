<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Borrowers;
use App\Models\User;

class Offices extends Model
{
    //

    protected $table = 'offices';

    protected $fillable = [
        'office_name',
        'is_deleted',
        'deleted_by'
    ];

    public function borrowers(){
        return $this->hasMany(Borrowers::class, 'office_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
