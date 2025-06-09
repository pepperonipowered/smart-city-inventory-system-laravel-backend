<?php

namespace App\Models;

use App\Models\BorrowTransactions;
use Illuminate\Database\Eloquent\Model;

class BorrowTransactionItems extends Model
{
    protected $table = 'borrow_transaction_items';

    protected $fillable = [
        'transaction_id',
        'item_copy_id',
        'returned',
        'returned_date',
        'item_type',
        'quantity'
    ];

    public function borrowTransactions(){
        return $this->belongsTo(BorrowTransactions::class, 'transaction_id');
    }
}
