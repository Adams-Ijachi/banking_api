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
    ];

    protected $casts = [
        "account_balance" => "double",
        
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
