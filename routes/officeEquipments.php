<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficeEquipmentsController;

Route::get('/office_equipments', [OfficeEquipments::class, 'index']);

Route::post('/office_equipments', [OfficeEquipments::class, 'store']);

Route::get('/office_equipments/{officeEquipment}', [OfficeEquipments:: class, 'show']);

Route::put('/office_equipments/{officeEquipment}', [OfficeEquipments:: class, 'update']);

Route::delete('/office_equipments/{officeEquipment}', [OfficeEquipments:: class, 'destroy']);
