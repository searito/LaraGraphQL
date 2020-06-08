<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationSetUpTest extends TestCase
{
    use RefreshDatabase;

    function test_it_creates_default_categories_on_user_creation(){
        $this->createClient();
        $response = $this->graphQL('mutation {
            register(input: {
                name: "Sear Clímaco",
                email: "sear@mail.com",
                password: "12345678",
                password_confirmation: "12345678"
            }){
                status
            }
        }');
        $user = User::first();
        $this->assertEquals(6, $user->categories->count());
    }
}
