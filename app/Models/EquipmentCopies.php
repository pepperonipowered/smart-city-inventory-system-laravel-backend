<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OfficeEquipments;

class EquipmentCopies extends Model
{
    //
    protected $table = 'equipment_copies';

    protected $fillable = [
        'item_id',
        'is_available',
        'copy_num',
        'serial_number',
    ];

    public function officeEquipments(){
        return $this->belongsTo(OfficeEquipments::class, 'item_id');
    }
}
