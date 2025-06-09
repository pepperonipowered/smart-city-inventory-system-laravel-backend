<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BorrowTransactionItems;
use App\Models\Borrowers;
use App\Models\User;

class BorrowTransactions extends Model
{
    //

    protected $table = 'borrow_transactions';

    protected $fillable = ['borrower_id', 'borrow_date', 'return_date', 'lender_id', 'remarks', 'is_deleted', 'isc'];

    public function borrowTransactionItems()
    {
        return $this->hasMany(BorrowTransactionItems::class, 'transaction_id', 'id');
    }

    public function borrowers()
    {
        return $this->belongsTo(Borrowers::class, 'borrower_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }
}
