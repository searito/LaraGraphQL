<?php

namespace Tests\Feature;

use App\Account;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AccountMutationsTest extends TestCase
{
    use RefreshDatabase;

    function test_it_creates_an_account(){
        //  Prepare
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        //  Execution
        $response = $this->graphQL('mutation {
            createAccount(input: {
                name: "wallet",
                balance: 25
            }){
                name
                balance
                user {
                    id
                    name
                }
            }
        }');
        //  Assertions
        $response->assertJson([
            'data'  => [
                'createAccount' => [
                    'name'      =>  'wallet',
                    'balance'   =>  25.00,
                    'user'      =>  [
                        'id'    =>  $user->id,
                        'name'  =>  $user->name
                    ]
                ]
            ]
        ]);
        $this->assertDatabaseHas('accounts', [
            'name'      =>  'wallet',
            'balance'   =>  '25',
            'user_id'   =>  $user->id
        ]);
    }

    function test_it_validates_input(){
        //  Prepare
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        //  Execution
        $response = $this->graphQL('mutation {
            createAccount(input: {
                name: "wallet",
                balance: asdfg
            }){
                name
                balance
                user {
                    id
                    name
                }
            }
        }');
        //  Assertions
        $response->assertJson([
            'errors'    =>  [
                [
                    "message"   => "Field \"createAccount\" argument \"input\" requires type Float!, found asdfg."
                ]
            ]
        ]);
        $this->assertDatabaseMissing('accounts', [
            'name'      =>  'wallet',
            'user_id'   =>  $user->id
        ]);
    }

    function test_it_validates_balance_no_less_than_0(){
        //  Prepare
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        //  Execution
        $response = $this->graphQL('mutation {
            createAccount(input: {
                name: "wallet",
                balance: -50
            }){
                name
                balance
                user {
                    id
                    name
                }
            }
        }');
        //  Assertions
        $response->assertJson([
            'errors'    =>  [
                [
                    "message"   =>  "Validation failed for the field [createAccount].",
                    "extensions"    =>  [
                        "validation"    =>  [
                            "input.balance" =>  [
                                "The input.balance must be greater than or equal 0."
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertDatabaseMissing('accounts', [
            'name'      =>  'wallet',
            'user_id'   =>  $user->id
        ]);
    }

    function test_it_can_update_an_account(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'name'      =>  'Wallet',
            'user_id'   =>  $user->id,
            'balance'   =>  100
        ]);
        Passport::actingAs($user);
        $response = $this->graphQL('mutation {
            updateAccount(id:'.$account->id.', input: {
                name: "Savings"
            }){
                id
                name
                balance
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'updateAccount' => [
                    'id'        =>  $account->id,
                    'name'      =>  'Savings',
                    'balance'   =>  $account->balance
                ]
            ]
        ]);
        $this->assertDatabaseHas('accounts', [
            'user_id'   =>  $user->id,
            'name'      =>  'Savings',
            'id'        =>  $account->id
        ]);
    }

    function test_it_can_update_an_account_when_no_owner(){
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'name'      =>  'Wallet',
            'user_id'   =>  $user->id,
            'balance'   =>  100
        ]);
        Passport::actingAs($user2);
        $response = $this->graphQL('mutation {
            updateAccount(id:'.$account->id.', input: {
                name: "Savings"
            }){
                id
                name
                balance
            }
        }');
        $response->assertJson([
            'errors'     =>  [
                [
                    "message"   =>  "You are not authorized to access updateAccount"
                ]
            ]
        ]);
        $this->assertDatabaseMissing('accounts', [
            'user_id'   =>  $user->id,
            'name'      =>  'Savings',
            'id'        =>  $account->id
        ]);
    }

    function test_it_can_delete_an_account(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'name'      =>  'Billetera',
            'user_id'   =>  $user->id,
            'balance'   =>  400
        ]);
        Passport::actingAs($user);
        $response = $this->graphQL('mutation {
            deleteAccount(id: '.$account->id.'){
                id
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'deleteAccount' =>  [
                    'id'    =>  $account->id
                ]
            ]
        ]);
        $deleteAccount = $account->fresh();
        $this->assertNotNull($deleteAccount->deleted_at);
    }
}
