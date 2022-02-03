<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AmountLessThanOrEqual;

use Auth;
use App\Models\{
    Account,

};

class TransferTransactionRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        $accountFrm = Account::where('account_number',$this->input('account_from'))->first(); // get account from account number

        if(!$accountFrm)  return false; // if account not found return false
        return $accountFrm->user_id == Auth::user()->id ? true : false;
      
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //make account_to different from logged in user account
            'account_from' => ['bail','required','numeric', 'exists:accounts,account_number'],
            'account_to' => ['bail','required','numeric', 'exists:accounts,account_number', 'different:account_from'],
            'amount' => ['bail','required','numeric', 'min:1',new AmountLessThanOrEqual($this->account_from)],

        ];
    }
}
