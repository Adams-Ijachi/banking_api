<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_frm",
        "account_to",
        "amount",
    ];

    protected $casts = [
        "amount" => "double",
    ];

    public function accountFrm()
    {
        return $this->belongsTo(Account::class, 'account_frm');
    }

    public function accountTo()
    {
        return $this->belongsTo(Account::class, 'account_to');
    }

    // get account_frm
    public function getSenderAccountNumberAttribute($value)
    {
        
        return Account::find($value)->account_number;
    }
    // get account_to
    public function getAccountToAttribute($value)
    {
        return Account::find($value)->account_number;
    }

    // get transactionType
    public function getTransactionTypeAttribute()
    {
       
        if($this->accountFrm->user_id == Auth::user()->id)
        {
            return 'Debit';
        }
        else
        {
            return 'Credit';
        }

    }



}
