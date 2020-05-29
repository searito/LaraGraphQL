<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AccountTest extends TestCase
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
}
