<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Events\UpdateInventoryData;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            return response()->json(Categories::all());
        } catch (\Exception $e) {
            return response()->json(['Index Categories Error' => $e->getMessage()], 500);
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
                'category_name' => 'required|string|max:255',
                'is_deleted' => 'required|boolean',
                'deleted_by' => 'nullable|exists:users,id',
            ]);

            $category = Categories::create($request->all());

            broadcast(new UpdateInventoryData('Categories created: ' . $category->id));

            return response()->json([
                'message' => 'Successfully Created',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['Store Categories Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categories $categories)
    {
        //
        try {
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['Show Categories Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categories $categories)
    {
        //
        try {
            $request->validate([
                'category_name' => 'sometimes|required|string|max:255',
                'is_deleted' => 'sometimes|required|boolean',
                'deleted_by' => 'sometimes|nullable|exists:users,id',
            ]);

            $categories->update($request->all());

            broadcast(new UpdateInventoryData('Categories updated: ' . $categories->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Categories Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categories $categories)
    {
        //
        try {
            $categories->delete();

            broadcast(new UpdateInventoryData('Categories deleted'));

            return response()->json(['message' => 'Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['Destroy Categories Error' => $e->getMessage()], 500);
        }
    }
}
