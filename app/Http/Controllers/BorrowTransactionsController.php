<?php

namespace App\Http\Controllers;

use App\Models\BorrowTransactions;
use Illuminate\Http\Request;
use App\Events\UpdateInventoryData;

class BorrowTransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            return response()->json(BorrowTransactions::all());
        } catch (\Exception $e) {
            return response()->json(['Index Borrow Transactions Error' => $e->getMessage()], 500);
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
                'borrower_id' => 'required|exists:borrowers,id',
                'borrow_date' => 'nullable|date',
                'returned_date' => 'nullable|date',
                'lender_id' => 'required|exists:users,id',
                'remarks' => 'required|string',
                'isc' => 'required|string|max:255',
            ]);

            $borrowTransaction = BorrowTransactions::create($request->all());

            broadcast(new UpdateInventoryData('Borrow Transactions created: ' . $borrowTransaction->id));

            return response()->json(
                [
                    'message' => 'Successfully Created',
                    'data' => $borrowTransaction,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['Store Borrow Transactions Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowTransactions $borrowTransactions)
    {
        //
        try {
            return response()->json($borrowTransactions);
        } catch (\Exception $e) {
            return response()->json(['Show Borrow Transaction Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowTransactions $borrowTransactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowTransactions $borrowTransactions)
    {
        //
        try {
            $request->validate([
                'borrower_id' => 'sometimes|required|exists:borrowers,id',
                'borrow_date' => 'sometimes|nullable|date',
                'returned_date' => 'sometimes|nullable|date',
                'lender_id' => 'sometimes|required|exists:users,id',
                'remarks' => 'sometimes|required|string',
                'isc' => 'sometimes|required|string|max:255',
            ]);
            $borrowTransactions->update($request->all());

            broadcast(new UpdateInventoryData('Borrow Transaction updated: ' . $borrowTransactions->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => $borrowTransactions
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Borrow Transaction Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowTransactions $borrowTransactions)
    {
        //
        try {
            $borrowTransactions->delete();

            broadcast(new UpdateInventoryData('Borrow Transaction deleted'));

            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['Destroy Borrow Transaction Error' => $e->getMessage()], 500);
        }
    }

    public function storeAndLoad(Request $request)
    {
        try {
            $request->validate([
                'borrower_id' => 'required|exists:borrowers,id',
                'borrow_date' => 'nullable|date',
                'return_date' => 'nullable|date',
                'lender_id' => 'required|exists:users,id',
                'remarks' => 'required|string',
                'isc' => 'required|string|max:255',
            ]);

            $borrowTransaction = BorrowTransactions::create($request->all());

            // Eager load related models
            $borrowTransaction->load(['borrowers', 'user', 'borrowTransactionItems']);

            return response()->json([
                'message' => 'Successfully Created with Relationships',
                'data' => $borrowTransaction,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['Store and Load Borrow Transaction Error' => $e->getMessage()], 500);
        }
    }
}
