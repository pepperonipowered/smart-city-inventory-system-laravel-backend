<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Offices;
use App\Models\User;

class Borrowers extends Model
{
    //

    protected $table = 'borrowers';

    protected $fillable = [
        'borrowers_name',
        'borrowers_contact',
        'office_id',
        'is_deleted',
        'deleted_by'
    ];

    public function offices()
    {
        return $this->belongsTo(Offices::class, 'office_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
