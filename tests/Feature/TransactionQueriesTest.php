<?php

namespace Tests\Feature;

use App\Account;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionQueriesTest extends TestCase
{
    use RefreshDatabase;

    function test_it_queries_transactions(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id
        ]);
        $transactions = factory(Transaction::class, 20)->create([
            'account_id'    =>  $account->id
        ]);
        Passport::actingAs($user);
        $response = $this->graphQL('query {
            transactions(first: 20) {
                data {
                    id
                    amount
                    type
                    account {
                        id
                    }
                }
                paginatorInfo {
                    currentPage
                }
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'transactions'  =>  [
                    'data'          =>  [],
                    'paginatorInfo' =>  []
                ]
            ]
        ]);
    }

    function test_it_queries_logged_in_user_transactions(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id
        ]);
        $transactions = factory(Transaction::class, 10)->create([
            'account_id'    =>  $account->id
        ]);
        factory(Transaction::class, 30)->create();
        Passport::actingAs($user);
        $response = $this->graphQL('query {
            transactions(first: 10) {
                data {
                    id
                    amount
                    type
                    account {
                        id
                    }
                }
                paginatorInfo {
                    currentPage
                    total
                }
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'transactions'  =>  [
                    'data'          =>  [],
                    'paginatorInfo' =>  [
                        'total' =>  10
                    ]
                ]
            ]
        ]);
    }

    function test_it_queries_a_transaction(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id
        ]);
        $transactions = factory(Transaction::class, 10)->create([
            'account_id'    =>  $account->id
        ]);
        $transaction = $transactions->shuffle()->first();
        Passport::actingAs($user);
        $response = $this->graphQL('query {
            transaction(id: '.$transaction->id.') {
                id
                amount
                type
                account {
                    id
                }
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'transaction'  =>  [
                    'id'        =>  $transaction->id,
                    'amount'    =>  $transaction->amount,
                    'type'      =>  $transaction->type,
                    'account'   =>  [
                        'id'    =>  $account->id
                    ]
                ]
            ]
        ]);
    }

    function test_it_cant_queries_a_transaction_where_not_owner(){
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id'   =>  $user->id
        ]);
        factory(Transaction::class, 10)->create([
            'account_id'    =>  $account->id
        ]);
        $transactions = factory(Transaction::class, 30)->create();
        $transaction = $transactions->shuffle()->first();
        Passport::actingAs($user);
        $response = $this->graphQL('query {
            transaction(id: '.$transaction->id.') {
                id
                amount
                type
                account {
                    id
                }
            }
        }');
        $response->assertJson([
            'errors'    =>  [
                [
                    'message'   =>  "You are not authorized to access transaction"
                ]
            ]
        ]);
    }
}
