<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_number",
        "account_balance",
        "user_id",
        "account_type",
    ];

    protected $casts = [
        "account_balance" => "double",

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // scope AccountNumberExists
    public function scopeAccountNumberExists($query, $account_number)
    {
        return $query->where('account_number', $account_number);
    }

    // hasmany transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'accountFrm');
    }

    // hasmany transactions

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'account_to');
    }

    



}
