<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use App\Models\EquipmentCopies;

class OfficeEquipments extends Model
{
    //

    protected $table = 'office_equipments';

    protected $fillable = [
        'equipment_name',
        'equipment_description',
        'category_id',
        'image_path',
        'isc'

    ];

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function equipmentCopies()
    {
        return $this->hasMany(EquipmentCopies::class);
    }
}
