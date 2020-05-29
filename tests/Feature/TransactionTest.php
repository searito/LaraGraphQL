<?php

namespace Tests\Feature;

use App\Account;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    function test_it_creates_transaction_and_updates_balance(){
        //  Prepare
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id,
            'balance'   =>  0
        ]);
        Passport::actingAs($user);
        //  Execute
        $response = $this->graphQL('mutation {
            createTransaction(input: {
                account_id: '.$account->id.',
                type: INCOME,
                amount: 100,
                description: "Income"
            }){
                type
                amount
                description
                account {
                    id
                    name
                    balance
                }
            }
        }');
        //  Assert
        $response->assertJson([
            'data'  =>  [
                'createTransaction' =>  [
                    'type'          =>  'INCOME',
                    'amount'        =>  100.00,
                    'description'   =>  'Income',
                    'account'   =>  [
                        'id'        =>  $account->id,
                        'name'      =>  $account->name,
                        'balance'   =>  100.00
                    ]
                ]
            ]
        ]);
    }
}
