<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';

    protected $fillable = [
        'category',
        'user_id',
        'action',
        'data',
        'description',
        'is_deleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
