<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Faker;

class UserLoginTest extends TestCase
{
    use WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUserLogin()
    {
        $faker = Faker\Factory::create();
        $password = $faker->randomNumber($nbDigits = 6);
        $user = factory(User::class)->create([
            'password' => $password
        ]);
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', '/api/user/login', ['email' => $user->email, 'password' => $password]);
        $response->assertSuccessful();
        $user->delete();
    }
    public function testUserLoginEmailNotVerified()
    {
        $faker = Faker\Factory::create();
        $password = $faker->randomNumber($nbDigits = 6);
        $user = factory(User::class)->create([
            'email_verified_at' => null,
            'password' => $password
        ]);
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', '/api/user/login', ['email' => $user->email, 'password' => $password]);
        $response->assertStatus(400);
        $user->delete();
    }

}
