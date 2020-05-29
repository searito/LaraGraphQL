<?php

namespace Tests\Feature;

use App\Account;
use App\Transaction;
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

    function test_it_creates_transaction_and_updates_balance_with_expense(){
        //  Prepare
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id,
            'balance'   =>  100
        ]);
        Passport::actingAs($user);
        //  Execute
        $response = $this->graphQL('mutation {
            createTransaction(input: {
                account_id: '.$account->id.',
                type: EXPENSE,
                amount: 50,
                description: "Expense"
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
                    'type'          =>  'EXPENSE',
                    'amount'        =>  50.00,
                    'description'   =>  'Expense',
                    'account'   =>  [
                        'id'        =>  $account->id,
                        'name'      =>  $account->name,
                        'balance'   =>  50.00
                    ]
                ]
            ]
        ]);
    }

    function test_it_can_update_a_transaction(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id,
            'balance'   =>  100
        ]);
        $transaction = factory(Transaction::class)->state('income')->create([
            'account_id'    =>  $account->id,
            'amount'        =>  50
        ]);
        $this->assertEquals(150, $account->fresh()->balance);
        Passport::actingAs($user);

        $response = $this->graphQL('mutation {
            updateTransaction(id:'.$transaction->id.', input: {
                amount: 20
            }){
                description
                type
                amount
                account{
                    id
                    name
                    balance
                }
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'updateTransaction' =>  [
                    'amount'    =>  20.00,
                    'account'   => [
                        'balance'   =>  120.00
                    ]
                ]
            ]
        ]);
    }

    function test_it_can_update_a_transaction_case_2(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id,
            'balance'   =>  100
        ]);
        $transaction = factory(Transaction::class)->state('expense')->create([
            'account_id'    =>  $account->id,
            'amount'        =>  50
        ]);
        $this->assertEquals(50, $account->fresh()->balance);
        Passport::actingAs($user);

        $response = $this->graphQL('mutation {
            updateTransaction(id:'.$transaction->id.', input: {
                amount: 20
            }){
                description
                type
                amount
                account{
                    id
                    name
                    balance
                }
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'updateTransaction' =>  [
                    'amount'    =>  20.00,
                    'account'   => [
                        'balance'   =>  80.00
                    ]
                ]
            ]
        ]);
    }
}
