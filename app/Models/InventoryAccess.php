<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InventoryAccess extends Model
{
    //
    protected $table = 'inventory_accesses';

    protected $fillable = [
        'for_dashboard',
        'for_transactions',
        'for_inventory',
        'for_offices',
        'for_categories',
        'for_borrowers',
        'for_users',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
