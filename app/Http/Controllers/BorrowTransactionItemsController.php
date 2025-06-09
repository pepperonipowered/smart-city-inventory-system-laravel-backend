<?php

namespace App\Http\Controllers;

use App\Models\BorrowTransactionItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\UpdateInventoryData;

class BorrowTransactionItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            return response()->json(BorrowTransactionItems::all());
        } catch (\Exception $e) {
            return response()->json(['Index Borrow Transaction Items Error' => $e->getMessage()], 500);
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
        try {
            Log::info('REQUEST BODY', $request->all());
            $request->validate([
                'transaction_id' => 'required|exists:borrow_transactions,id',
                'items' => 'required|array',
                'items.*.item_copy_id' => 'required|integer',
                'items.*.returned' => 'required|boolean',
                'items.*.returned_date' => 'nullable|date',
                'items.*.item_type' => 'required|string',
                'items.*.quantity' => 'required|integer'
            ]);

            $transactionId = $request->transaction_id;
            $items = $request->items; // This is now an array

            $data = [];
            $now = now();

            foreach ($items as $value) {
                $data[] = [
                    'transaction_id' => $transactionId,
                    'item_copy_id' => $value['item_copy_id'],
                    'returned' => $value['returned'],
                    'returned_date' => $value['returned_date'], // Fix: Use `$value` instead of `$item`
                    'item_type' => $value['item_type'],
                    'quantity' => $value['quantity'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            BorrowTransactionItems::insert($data);

            broadcast(new UpdateInventoryData('Borrow Transaction Items created: ' . $transactionId->id));

            return response()->json([
                'message' => 'Successfully Created',
                'data' => $data, // Return the inserted data
            ], 201);
        } catch (\Exception $e) {
            Log::error('STORE BORROW TRANSACTION ITEMS ERROR: ' . $e->getMessage());
            return response()->json(['Store Borrow Transaction Items Error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(BorrowTransactionItems $borrowTransactionItems)
    {
        //
        try {
            return response()->json($borrowTransactionItems);
        } catch (\Exception $e) {
            return response()->json(['Show Borrow Transaction Items Error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowTransactionItems $borrowTransactionItems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowTransactionItems $borrowTransactionItems)
    {
        //
        try {
            $request->validate([
                'transaction_id' => 'sometimes|required|exists:borrow_transactions,id',
                'item_copy_id' => 'sometimes|required|integer',
                'returned' => 'sometimes|required|boolean',
                'returned_date' => 'sometimes|nullable|date',
                'item_type' => 'sometimes|required|string',
                'quantity' => 'sometimes|required|integer'
            ]);
            $borrowTransactionItems->update($request->all());

            broadcast(new UpdateInventoryData('Borrow Transaction Items updated: ' . $borrowTransactionItems->id));

            return response()->json([
                'message' => 'Successfully Updated',
                'data' => $borrowTransactionItems
            ]);
        } catch (\Exception $e) {
            return response()->json(['Update Borrow Transaction Items Error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowTransactionItems $borrowTransactionItems)
    {
        //
        try {
            $borrowTransactionItems->delete();
            broadcast(new UpdateInventoryData('Borrow Transaction Items deleted'));

            return response()->json([
                'message' => 'Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['Destroy Borrow Transaction Items Error' => $e->getMessage()], 500);
        }
    }
}
