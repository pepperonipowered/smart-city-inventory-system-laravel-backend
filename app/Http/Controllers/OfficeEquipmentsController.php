<?php

namespace App\Http\Controllers;

use App\Models\OfficeEquipments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\UpdateInventoryData;

class OfficeEquipmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            return response()->json(OfficeEquipments::all());
        } catch (\Exception $e) {
            return response()->json(['Index Office Equipments Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $request->validate([
                'equipment_name' => 'required|string|max:255',
                'equipment_description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'isc' => 'required|string|max:255',
                'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = $request->except('image');

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('equipments', 'public');
                $data['image_path'] = $path;
            }

            $equipment = OfficeEquipments::create($data);

            broadcast(new UpdateInventoryData('Office Equipment created: ' . $equipment->id));

            return response()->json([
                'message' => 'Successfully Created',
                'data' => [
                    'equipment' => $equipment,
                    'image_url' => isset($path) ? asset('storage/' . $path) : null
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['Store Office Equipment Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficeEquipments $officeEquipments)
    {
        //
        try {
            return response()->json($officeEquipments);
        } catch (\Exception $e) {
            return response()->json(['Show Office Equipments Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfficeEquipments $officeEquipments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfficeEquipments $officeEquipments)
    {
        //
        try {
            $request->validate([
                'equipment_name' => 'sometimes|required|string|max:255',
                'equipment_description' => 'sometimes|required|string',
                'category_id' => 'sometimes|required|exists:categories,id',
                'isc' => 'sometimes|required|string|max:255',
                'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = $request->except('image');

            if ($request->hasFile('image')) {
                // Delete old image
                if ($officeEquipments->image_path) {
                    Storage::disk('public')->delete($officeEquipments->image_path);
                }

                $path = $request->file('image')->store('equipments', 'public');
                $data['image_path'] = $path;
            }

            $officeEquipments->update($data);

            broadcast(new UpdateInventoryData('Office Equipment updated: ' . $officeEquipments->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => [
                    'equipment' => $officeEquipments,
                    'image_url' => $officeEquipments->image_path ? asset('storage/' . $officeEquipments->image_path) : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Office Equipment Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeEquipments $officeEquipments)
    {
        //
        try {
            $officeEquipments->delete();

            broadcast(new UpdateInventoryData('Office Equipment deleted'));

            return response()->json(['message' => 'Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['Destroy Office Equipment Error' => $e->getMessage()], 500);
        }
    }
}
