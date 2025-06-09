<?php

namespace App\Http\Controllers;

use App\Models\InventoryAccess;
use Illuminate\Http\Request;
use App\Events\UpdateInventoryData;

class InventoryAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            return response()->json(InventoryAccess::all());
        } catch (\Exception $e) {
            return response()->json(['Index Inventory Access Error' => $e->getMessage()], 500);
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
                'for_dashboard' => 'required|boolean',
                'for_transactions' => 'required|boolean',
                'for_inventory' => 'required|boolean',
                'for_offices' => 'required|boolean',
                'for_categories' => 'required|boolean',
                'for_borrowers' => 'required|boolean',
                'for_users' => 'required|boolean',
                'user_id' => 'required|exists:users,id',
            ]);

            $inventoryAccess = InventoryAccess::create($request->all());

            broadcast(new UpdateInventoryData('Inventory Access created: ' . $inventoryAccess->id));

            return response()->json([
                'message' => 'Successfully Created',
                'data' => $inventoryAccess
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['Store Inventory Access Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryAccess $inventoryAccess)
    {
        //
        try {
            return response()->json($inventoryAccess);
        } catch (\Exception $e) {
            return response()->json(['Show Inventory Access Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryAccess $inventoryAccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryAccess $inventoryAccess)
    {
        //
        try {
            $request->validate([
                'for_dashboard' => 'sometimes|required|boolean',
                'for_transactions' => 'sometimes|required|boolean',
                'for_inventory' => 'sometimes|required|boolean',
                'for_offices' => 'sometimes|required|boolean',
                'for_categories' => 'sometimes|required|boolean',
                'for_borrowers' => 'sometimes|required|boolean',
                'for_users' => 'sometimes|required|boolean',
                'user_id' => 'sometimes|required|exists:users,id',
            ]);

            $inventoryAccess->update($request->all());

            broadcast(new UpdateInventoryData('Inventory Access updated: ' . $inventoryAccess->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => $inventoryAccess
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Inventory Access Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryAccess $inventoryAccess)
    {
        //
        try {
            $inventoryAccess->delete();

            broadcast(new UpdateInventoryData('Inventory Access deleted'));

            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['Destroy Inventory Access Error' => $e->getMessage()], 500);
        }
    }
}
