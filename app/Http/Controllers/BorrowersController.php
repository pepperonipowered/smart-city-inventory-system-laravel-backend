<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use Illuminate\Http\Request;
use App\Events\UpdateInventoryData;

class BorrowersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            return response()->json(Borrowers::all());
        } catch (\Exception $e) {
            return response()->json(['Index Borrowers Error' => $e->getMessage()], 500);
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
                'borrowers_name' => 'required|string|max:255',
                'borrowers_contact' => 'required|string',
                'office_id' => 'required|exists:offices,id',
                'deleted_by' => 'nullable|exists:users,id',
                'is_deleted' => 'required|boolean'
            ]);

            $borrower = Borrowers::create($request->all());

            broadcast(new UpdateInventoryData('Borrower created: ' . $borrower->id));

            return response()->json(
                [
                    'message' => 'Successfully Created',
                    'data' => $borrower,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['Store Borrowers Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowers $borrowers)
    {
        //
        try {
            return response()->json($borrowers);
        } catch (\Exception $e) {
            return response()->json(['Show Borrowers Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowers $borrowers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowers $borrowers)
    {
        //
        try {
            $request->validate([
                'borrowers_name' => 'sometimes|required|string|max:255',
                'borrowers_contact' => 'sometimes|required|string',
                'office_id' => 'sometimes|required|exists:offices,id',
                'is_deleted' => 'sometimes|required|boolean',
                'deleted_by' => 'sometimes|nullable|exists:users,id',
            ]);
            $borrowers->update($request->all());

            broadcast(new UpdateInventoryData('Borrower updated: ' . $borrowers->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => $borrowers
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Borrowers Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowers $borrowers)
    {
        //
        try {
            $borrowers->delete();

            broadcast(new UpdateInventoryData('Borrower deleted'));

            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['Destroy Borrowers Error' => $e->getMessage()], 500);
        }
    }
}
