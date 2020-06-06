<?php

namespace Tests\Feature;

use App\Account;
use App\Category;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionMutationsTest extends TestCase
{
    use RefreshDatabase;

    function test_it_creates_transaction_and_updates_balance(){
        //  Prepare
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id,
            'balance'   =>  0
        ]);
        $category = factory(Category::class)->create(['user_id' => $user->id]);
        Passport::actingAs($user);
        //  Execute
        $response = $this->graphQL('mutation {
            createTransaction(input: {
                account_id: '.$account->id.',
                category_id: '.$category->id.',
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
        $category = factory(Category::class)->create([
            'user_id'   =>  $user->id
        ]);
        Passport::actingAs($user);
        //  Execute
        $response = $this->graphQL('mutation {
            createTransaction(input: {
                account_id: '.$account->id.',
                category_id: '.$category->id.',
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
            'user_id' => $user->id,
            'balance' => 100
        ]);
        $transaction = factory(Transaction::class)->state('expense')->create([
            'account_id' => $account->id,
            'amount' => 50
        ]);
        $this->assertEquals(50, $account->fresh()->balance);
        Passport::actingAs($user);
        $response = $this->graphQL('mutation {
            updateTransaction(id: '.$transaction->id.', input: {
                amount: 20
            }){
                description
                type
                amount
                account {
                    id
                    name
                    balance
                }
            }
        }');
        $response->assertJson([
            'data' => [
                'updateTransaction' => [
                    'amount' => 20.00,
                    'account' => [
                        'balance' => 80.00
                    ]
                ]
            ]
        ]);
    }

    function test_it_cant_update_a_transaction_when_not_owner(){
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id,
            'balance'   =>  100
        ]);
        $transaction = factory(Transaction::class)->state('expense')->create([
            'account_id'    =>  $account->id,
            'amount'        =>  50
        ]);
        $this->assertEquals(50, $account->fresh()->balance);
        Passport::actingAs($user2);
        $response = $this->graphQL('mutation {
            updateTransaction(id: '.$transaction->id.', input: {
                amount: 20
            }){
                description
                type
                amount
                account {
                    id
                    name
                    balance
                }
            }
        }');
        $response->assertJson([
            'errors'    => [
                [
                    "message"   =>  "You are not authorized to access updateTransaction"
                ]
            ]
        ]);
    }

    function test_it_can_delete_a_transaction(){
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
            deleteTransaction(id:'.$transaction->id.'){
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
                'deleteTransaction' =>  [
                    'account'   => [
                        'balance'   =>  100.00
                    ]
                ]
            ]
        ]);
    }

    function test_it_can_delete_a_transaction_case_2(){
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
            deleteTransaction(id:'.$transaction->id.'){
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
                'deleteTransaction' =>  [
                    'account'   => [
                        'balance'   =>  100.00
                    ]
                ]
            ]
        ]);
    }
}
