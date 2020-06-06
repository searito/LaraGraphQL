<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryQueriesTest extends TestCase
{
    use RefreshDatabase;

    function test_it_queries_categories(){
        $user = factory(User::class)->create();
        factory(Category::class, 5)->create([
            'user_id'   =>  $user->id
        ]);
        Passport::actingAs($user);
        $response = $this->graphQL('query {
            categories(first: 20){
                data {
                    id
                    name
                }
                paginatorInfo {
                    total
                }
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'categories'  =>  [
                    'data'  =>  [],
                    'paginatorInfo' =>  [
                        'total' =>  5
                    ]
                ]
            ]
        ]);
    }

    function test_it_queries_a_category(){
        $user = factory(User::class)->create();
        $categories = factory(Category::class, 5)->create([
            'user_id'   =>  $user->id
        ]);
        $category = $categories->shuffle()->first();
        Passport::actingAs($user);
        $response = $this->graphQL('query {
            category(id: '.$category->id.'){
                id
                name
            }
        }');
        $response->assertJson([
            'data'  =>  [
                'category'   =>  [
                    'id'    =>  $category->id,
                    'name'  =>  $category->name
                ]
            ]
        ]);
    }

    function test_it_cant_query_an_category_not_owned(){
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        Passport::actingAs($user);
        $response = $this->graphQL('query {
            category(id: '.$category->id.') {
                id
                name
            }
        }');
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'You are not authorized to access category'
                ]
            ]
        ]);
    }
}
