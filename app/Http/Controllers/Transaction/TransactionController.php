<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\TransactionResource;

use App\Http\Requests\{
    TransferTransactionRequest,
  
};

use App\Models\{
    Account,
    Transaction
};

use Auth;

class TransactionController extends Controller
{
    //

    public function Transfer(TransferTransactionRequest $request)
    {
        # code...
        $validated = $request->validated();



        
        $accountFrm = Account::where('account_number',$validated['account_from'])->first(); // get account from account number
        $accountTo = Account::where('account_number',$validated['account_to'])->first(); // get account to account numbers
        

        $transaction = Transaction::create([
            'account_frm' => $accountFrm->id,
            'account_to' => $accountTo->id,
            'amount' => $validated['amount'],
        ]);

        // Deduct from account_frm (debit)
        $accountFrm->account_balance -= $validated['amount'];
        $accountFrm->save();

        // Add to account_to (credit)
        $accountTo->account_balance += $validated['amount'];
        $accountTo->save();

        // pass in message
        return response()->json(['message' => 'Transaction created successfully.','data'=>new TransactionResource($transaction)],201);

    }

    // transaction history
    public function transactionHistory(Request $request, $account_number)
    {
        # code...
        $account = Account::where('account_number',$account_number)->first();
        // check if authenticated user is the owner of the account
        if(Auth::user()->id != $account->user_id) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }


        $transactions = Transaction::where('account_frm',$account->id)->orWhere('account_to',$account->id)->get();
        return response()->json(['message' => 'Transaction history fetched successfully.','data'=>TransactionResource::collection($transactions)],200);
    }

}
