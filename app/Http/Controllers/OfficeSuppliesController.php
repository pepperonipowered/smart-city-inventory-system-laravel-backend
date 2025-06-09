<?php

namespace App\Http\Controllers;

use App\Models\OfficeSupplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Events\UpdateInventoryData;


class OfficeSuppliesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            return response()->json(OfficeSupplies::all());
        } catch (\Exception $e) {
            return response()->json(['Index Office Supplies Error' => $e->getMessage()], 500);
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
                'supply_name' => 'required|string|max:255',
                'supply_description' => 'required|string',
                'category_id' => 'nullable|exists:categories,id',
                'supply_quantity' => 'required|integer',
                'isc' => 'required|string|max:255',
                'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = $request->except('image');

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('supplies', 'public');
                $data['image_path'] = $path;
            }

            $officeSupply = OfficeSupplies::create($data);

            broadcast(new UpdateInventoryData('Office Supply created: ' . $officeSupply->id));

            return response()->json([
                'message' => 'Successfully Created',
                'data' => [
                    'supply' => $officeSupply,
                    'image_url' => isset($path) ? asset('storage/' . $path) : null
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['Store Office Supply Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficeSupplies $officeSupplies)
    {
        //
        try {
            return response()->json($officeSupplies);
        } catch (\Exception $e) {
            return response()->json(['Show Borrow Transaction Items Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfficeSupplies $officeSupplies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfficeSupplies $officeSupplies)
    {
        //
        try {
            $request->validate([
                'supply_name' => 'sometimes|required|string|max:255',
                'supply_description' => 'sometimes|required|string',
                'category_id' => 'sometimes|nullable|exists:categories,id',
                'supply_quantity' => 'sometimes|required|integer',
                'isc' => 'sometimes|required|string|max:255',
                'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = $request->except('image');

            if ($request->hasFile('image')) {
                // Delete old image
                if ($officeSupplies->image_path) {
                    Storage::disk('public')->delete($officeSupplies->image_path);
                }

                $path = $request->file('image')->store('supplies', 'public');
                $data['image_path'] = $path;
            }

            $officeSupplies->update($data);
            broadcast(new UpdateInventoryData('Office Supply updated: ' . $officeSupplies->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => [
                    'supply' => $officeSupplies,
                    'image_url' => $officeSupplies->image_path ? asset('storage/' . $officeSupplies->image_path) : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Office Supplies Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeSupplies $officeSupplies)
    {
        //
        try {
            $officeSupplies->delete();

            broadcast(new UpdateInventoryData('Office Supply deleted'));

            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['Destroy Office Supplies Error' => $e->getMessage()], 500);
        }
    }

    public function getOfficeSupplyById($itemId)
    {
        //
        try {
            Log::info("Fetching supply: {$itemId}");

            $supply = OfficeSupplies::with('categories')
                ->findOrFail($itemId);

            if ($supply) {
                Log::info("Supply found:", $supply->toArray());
            } else {
                Log::info("No supply found for id: {$itemId}");
            }

            return response()->json([
                'message' => 'Successfully fetched supply',
                'data' => $supply
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching supply for item_id: {$itemId}", ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch supply'], 500);
        }
    }

    public function getAllOfficeSuppliesWithRelations()
    {
        //
        try {
            Log::info("Fetching supplies");

            $perPage = request()->input('per_page', 8);
            $categoryIds = request()->input('category_ids'); // Expecting comma-separated values like "1,2,3"
            $search = request()->input('search');

            $query = OfficeSupplies::with('categories');

            // Multi-category filter
            if ($categoryIds) {
                $idsArray = explode(',', $categoryIds);
                $query->whereIn('category_id', $idsArray);
            }

            // Search filter
            if ($search) {
                $query->where('supply_name', 'LIKE', '%' . $search . '%');
            }

            // Paginate
            $supplies = $query->paginate($perPage);

            if ($supplies->isNotEmpty()) {
                Log::info("Supplies found:", $supplies->items());
            } else {
                Log::info("No supplies found");
            }

            return response()->json([
                'message' => 'Successfully fetched supplies',
                'data' => $supplies
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching supplies", ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch supplies'], 500);
        }
    }
}
