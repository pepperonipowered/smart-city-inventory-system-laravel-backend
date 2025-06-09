<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OfficeSupplies;
use App\Models\OfficeEquipments;
use App\Models\User;

class Categories extends Model
{
    //

    protected $table = 'categories';

    protected $fillable = ['category_name', 'is_deleted', 'deleted_by'];

    public function officeEquipments()
    {
        return $this->hasMany(OfficeEquipments::class, 'category_id');
    }

    public function officeSupplies()
    {
        return $this->hasMany(OfficeSupplies::class, 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
