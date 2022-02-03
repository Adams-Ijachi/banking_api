<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Models\{
    User,
    Account,
    Transaction
};

class AccountTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_account()
    {

        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->withHeaders([
            'x-api-key' => env('API_KEY'),
        ])->postJson('/api/v1/createAccount', [
            'amount' => 100,
            'account_type' => 'savings',
        ]); 

        $response
                ->assertStatus(201)
                ->assertJson(fn (AssertableJson $json) => 
                    $json->where('message', 'Account created successfully.')
                        ->has('data',fn ($json) => 
                            $json->has('account_number')
                                ->has('account_balance')
                                ->has('account_type')
                                ->has('user_id')
                                ->etc()
                        )
            );

    }

    public function test_transfer()
    {
        $user_1 = User::factory()->create();
        $user_2 = User::factory()->create();
        $account = Account::factory()->create([
            'account_type' => 'savings',
            'account_balance' => 100,
            'user_id' => $user_1->id
        ]);
        $account2 = Account::factory()->create([
            'account_type' => 'savings',
            'account_balance' => 100,
            'user_id' => $user_2->id
        ]);

        

        $response = $this->actingAs($user_1)->withHeaders([
            'x-api-key' => env('API_KEY'),
        ])->postJson('/api/v1/transfer', [
            'account_from' => $account->account_number,
            'account_to' => $account2->account_number,
            'amount' => 50,
        ]); 



        $response
                ->assertStatus(201)
                ->assertJson(fn (AssertableJson $json) => 
                    $json->where('message', 'Transaction created successfully.')
                        ->has('data',fn ($json) => 
                            $json->where('receiver_account_number',  "$account2->account_number")
                                ->where('sender_account_number', "$account->account_number")
                                ->where('amount', 50)
                                ->has('transaction_type')
                                ->etc()
                        )
            );


    }


    public function test_transfer_history ()
    {
        # code...
        $user_1 = User::factory()->create();
        $user_2 = User::factory()->create();
        $account = Account::factory()->create([
            'account_type' => 'savings',
            'account_balance' => 100,
            'user_id' => $user_1->id
        ]);
        $account2 = Account::factory()->create([
            'account_type' => 'savings',
            'account_balance' => 100,
            'user_id' => $user_2->id
        ]);

     

        $transactions = Transaction::factory(2)->create(
            [
                'account_frm' => $account->id,
                'account_to' => $account2->id,
            
                'amount' => 100,
              
            ]
    
        );
        $account = Account::where('account_number',$account->account_number)->first();
        

        $response = $this->actingAs($user_1)->withHeaders([
            'x-api-key' => env('API_KEY'),
        ])->getJson('/api/v1/transactionHistory/'.$account->account_number);

        
        $response
                ->assertStatus(200)
                ->assertJson(fn (AssertableJson $json) => 
                    $json->where('message', 'Transaction history fetched successfully.')
                        ->has('data.0',fn ($json) => 
                            $json->where('receiver_account_number',  "$account2->account_number")
                                ->where('sender_account_number', "$account->account_number")
                                ->where('amount', 100)
                                ->has('transaction_type')
                                ->etc()
                        )
            );


    }


    public function test_account_balance()
    {
        # code...

        $user_1 = User::factory()->create();

        $account = Account::factory()->create([
            'account_type' => 'savings',
            'account_balance' => 100,
            'user_id' => $user_1->id
        ]);


        $response = $this->actingAs($user_1)->withHeaders([
            'x-api-key' => env('API_KEY'),
        ])->getJson('/api/v1/getAccountBalance/'.$account->account_number);


        $response
                ->assertStatus(200)
                ->assertJson(fn (AssertableJson $json) => 
                    $json->where('message', 'Account balance fetched successfully.')
                        ->where('data',100)
            );

    }
}
