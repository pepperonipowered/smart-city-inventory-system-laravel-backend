<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotline extends Model
{
    //
    protected $table = 'hotlines';

    protected $fillable = [
        'image_path',
        'name',
        'number',
        'email',
        'is_deleted'
    ];
}
