<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowers;
use App\Models\User;
use App\Models\BorrowTransactions;
use App\Models\Offices;
use App\Models\BorrowTransactionItems;
use App\Models\EquipmentCopies;
use App\Models\OfficeSupplies;
use App\Models\OfficeEquipments;
use App\Models\Categories;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        $borrowers = Borrowers::with('offices', 'createdBy')->get();
        $categories = Categories::with('officeEquipments', 'officeSupplies', 'createdBy')->get();
        $users = User::with('borrowers', 'categories', 'borrowersCreatedBy')->get();
        $borrow_transactions = BorrowTransactions::with(['borrowers', 'borrowTransactionItems', 'user'])->get();
        $borrow_transaction_items = BorrowTransactionItems::with(['borrowTransactions'])->get();
        $equipment_copies = EquipmentCopies::with(['officeEquipments'])->get();
        $office_supplies = OfficeSupplies::with(['categories'])->get();
        $office_equipments = OfficeEquipments::with(['categories'])->get();
        $offices = Offices::with(['borrowers'])->get();

        return response()->json([
            'users' => $users,
            'borrowers' => $borrowers,
            'categories' => $categories,
            'borrow_transactions' => $borrow_transactions,
            'borrow_transaction_items' => $borrow_transaction_items,
            'equipment_copies' => $equipment_copies,
            'office_supplies' => $office_supplies,
            'office_equipments' => $office_equipments,
            'offices' => $offices,
        ]);
    }

    public function update(Request $request, $transactionHistory)
    {
        try {
            $validatedData = $request->validate([
                'borrow_date' => 'required|date',
                'return_date' => 'nullable|date',
                'lender_id' => 'required|integer|exists:users,id',
                'borrowers_id' => 'required|integer|exists:borrowers,id',
                'remarks' => 'nullable|string',
                'is_deleted' => 'required|boolean',
                'borrow_transaction_items' => 'nullable|array',
                'borrow_transaction_items.*.id' => 'sometimes|integer|exists:borrow_transaction_items,id',
                'borrow_transaction_items.*.item_copy_id' => 'required|integer',
                'borrow_transaction_items.*.returned' => 'required|boolean',
                'borrow_transaction_items.*.item_type' => 'required|string',
            ]);

            $transaction = BorrowTransactions::findOrFail($transactionHistory);
            $transaction->update([
                'borrow_date' => $validatedData['borrow_date'],
                'return_date' => $validatedData['return_date'] ?? null,
                'lender_id' => $validatedData['lender_id'],
                'borrowers_id' => $validatedData['borrowers_id'],
                'remarks' => $validatedData['remarks'] ?? $transaction->remarks,
                'is_deleted' => $validatedData['is_deleted'],
            ]);

            if (isset($validatedData['borrow_transaction_items'])) {
                foreach ($validatedData['borrow_transaction_items'] as $itemData) {
                    if (isset($itemData['id'])) {
                        $transactionItem = BorrowTransactionItems::find($itemData['id']);
                        if ($transactionItem) {
                            $transactionItem->update($itemData);
                        }
                    } else {
                        BorrowTransactionItems::create([
                            'transaction_id' => $transaction->id,
                            'item_copy_id' => $itemData['item_copy_id'],
                            'returned' => $itemData['returned'],
                            'item_type' => $itemData['item_type'],
                        ]);
                    }
                }
            }

            $borrowers = Borrowers::with('offices')->get();
            $categories = Categories::with('officeEquipments', 'officeSupplies', 'createdBy')->get();
            $users = User::with('borrowers')->get();
            $borrow_transactions = BorrowTransactions::with(['borrowers', 'borrowTransactionItems', 'user'])->get();
            $borrow_transaction_items = BorrowTransactionItems::with('borrowTransactions')->get();
            $equipment_copies = EquipmentCopies::with('officeEquipments')->get();
            $office_supplies = OfficeSupplies::with('categories')->get();
            $office_equipments = OfficeEquipments::with('categories')->get();
            $offices = Offices::with('borrowers')->get();

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully.',
                'data' => [
                    'users' => $users,
                    'borrowers' => $borrowers,
                    'categories' => $categories,
                    'borrow_transactions' => $borrow_transactions,
                    'borrow_transaction_items' => $borrow_transaction_items,
                    'equipment_copies' => $equipment_copies,
                    'office_supplies' => $office_supplies,
                    'office_equipments' => $office_equipments,
                    'offices' => $offices,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating transaction: ' . $e->getMessage(),
            ], 500);
        }
    }
}
