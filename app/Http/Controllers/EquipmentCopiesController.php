<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCopies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\UpdateInventoryData;

class EquipmentCopiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        try {
            return response()->json(EquipmentCopies::all());
        } catch (\Exception $e) {
            return response()->json(['Index Equipment Copies Error' => $e->getMessage()], 500);
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
                'item_id' => 'required|exists:office_equipments,id',
                'is_available' => 'required|boolean',
                'copy_num' => 'required|integer',
                'serial_number' => 'required|string',
            ]);

            $equipmentCopy = EquipmentCopies::create($request->all());

            broadcast(new UpdateInventoryData('Equipment Copy created: ' . $equipmentCopy->id));

            return response()->json([
                'message' => 'Successfully Created',
                'data' => $equipmentCopy
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['Store Equipment Copy Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentCopies $equipmentCopies)
    {
        //
        try {
            return response()->json($equipmentCopies);
        } catch (\Exception $e) {
            return response()->json(['Show Equipment Copies Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentCopies $equipmentCopies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentCopies $equipmentCopies)
    {
        //
        try {
            $request->validate([
                'item_id' => 'sometimes|required|exists:office_equipments,id',
                'is_available' => 'sometimes|required|boolean',
                'copy_num' => 'sometimes|required|integer',
                'serial_number' => 'sometimes|required|string',
            ]);
            $equipmentCopies->update($request->all());

            broadcast(new UpdateInventoryData('Equipment Copies updated: ' . $equipmentCopies->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => $equipmentCopies
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Equipment Copies Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentCopies $equipmentCopies)
    {
        //
        try {
            $equipmentCopies->delete();

            broadcast(new UpdateInventoryData('Equipment Copies deleted'));

            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['Destroy Equipment Copies Error' => $e->getMessage()], 500);
        }
    }
    /**
     * Get items by id and eager load relations.
     */
    public function getByItemId($itemId)
    {
        try {
            Log::info("Fetching equipment copies for item_id: {$itemId}");

            $copies = EquipmentCopies::with('officeEquipments.categories')
                ->where('item_id', $itemId)
                ->get();

            if ($copies) {
                Log::info("Equipment copy found:", $copies->toArray());
            } else {
                Log::info("No equipment copy found for item_id: {$itemId}");
            }

            return response()->json([
                'message' => 'Successfully fetched equipment copies',
                'data' => $copies
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching equipment copies for item_id: {$itemId}", ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch equipment copies'], 500);
        }
    }
    /**
     * Get all items and eager load relations.
     */
    public function getAllItemsWithRelations(Request $request)
    {
        try {
            // Get query params for pagination, search, category filtering
            $search = $request->query('search');
            $categoryIds = $request->query('category_ids'); // can be array or comma-separated string
            $perPage = $request->query('per_page', 8); // default 10 items per page
            $page = $request->query('page', 1);

            // Build the base query with eager loading
            $query = EquipmentCopies::with('officeEquipments.categories');

            // Filter by search if provided (assuming search on item name or code, adjust field as needed)
            if ($search) {
                $query->whereHas('officeEquipments', function($q) use ($search) {
                    $q->where('equipment_name', 'like', "%{$search}%");
                });
            }

            // Multi-category filter
            if ($categoryIds) {
                // Accept comma-separated or array input
                if (!is_array($categoryIds)) {
                    $categoryIds = explode(',', $categoryIds);
                }
                $query->whereHas('officeEquipments.categories', function($q) use ($categoryIds) {
                    $q->whereIn('id', $categoryIds);
                });
            }

           // Get paginated results (no need for uniqueness filtering)
            $paginated = $query->paginate($perPage, ['*'], 'page', $page);

            Log::info('Fetched all equipment copies with relations', ['data' => $paginated->items()]);

            return response()->json([
                'message' => 'Successfully fetched equipment copies with relations',
                'data' => $paginated
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching equipment copies with relations", ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch equipment copies with relations',
                'error' => $e->getMessage(), // <-- add this
                'trace' => $e->getTraceAsString(), // optional: for more details
            ], 500);
        }
    }


    public function getEquipmentCopyByItemAndCopyNum(Request $request)
    {
        $itemId = $request->query('item_id');
        $copyNum = $request->query('copy_num');

        try {
            // Fetch the equipment copy based on item_id and copy_num
            $copy = EquipmentCopies::with('officeEquipments.categories')->where('item_id', $itemId)
                ->where('copy_num', $copyNum)
                ->first();
            Log::info("Fetched equipment copy for item_id: {$itemId} and copy_num: {$copyNum}");
            return response()->json([
                'message' => 'Successfully fetched equipment copy details',
                'data' => $copy
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching equipment copy details.", ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch equipment copy details.'
            ], 500);
        }
    }
}
