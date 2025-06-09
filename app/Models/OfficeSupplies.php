<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;

class OfficeSupplies extends Model
{
    //
    protected $table = 'office_supplies';

    protected $fillable = [
        'supply_name',
        'supply_description',
        'category_id',
        'supply_quantity',
        'image_path',
        'isc'
    ];

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
