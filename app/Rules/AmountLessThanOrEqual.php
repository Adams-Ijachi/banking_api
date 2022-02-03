<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\{
    Account
};

class AmountLessThanOrEqual implements Rule
{

    protected $account_frm;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($account_number)
    {
        //
        $this->account_number = $account_number;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $account = Account::where('account_number', $this->account_number)->first();
        if ($account) {
            return $account->account_balance >= $value ? true : false;

        } else {
            return false;
        }   

      
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficent Funds.';
    }
}
