<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\{
    AccountCreationRequest,
  
};

use App\Models\{
    Account
};

use Auth;
class AccountController extends Controller
{
    //

    public function createAccount(AccountCreationRequest $request)
    {
        $validated = $request->validated();    
        $account = Account::create([
            'account_number' => $this->createAccountNumber(),
            'account_balance' => $validated['amount'],
            'user_id' => Auth::user()->id,
            'account_type' => $validated['account_type'],

        ]);
      
        return response()->json(['message' => 'Account created successfully.','data'=>$account],200);
    }



    // creates a new account for a user
    public function createAccountNumber()
    {
        $account_number = mt_rand(10000000, 99999999);
        $account_number = str_pad($account_number, 10, '0', STR_PAD_LEFT);

        if(Account::AccountNumberExists($account_number)->exists()) {
            return $this->createAccountNumber();
        }
        return $account_number;
        
    }
}
