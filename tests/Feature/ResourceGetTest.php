<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ResourceGetTest extends TestCase
{
    use WithFaker;

    protected $user;

    public function setUp() :void
    {
        parent::setUp();
        $password = $this->faker->randomNumber($nbDigits = 6);
        $user = factory(User::class)->create([
            'password' => $password,
        ]);
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('POST', '/api/user/login', ['email' => $user->email, 'password' => $password]);
        $this->user = $user;
    }
    public function testgetUserMain()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('GET', '/api/user/main');
        $response->assertSuccessful();
    }
    public function testgetUserTransfers()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('GET', '/api/user/main/transfers');
        $response->assertSuccessful();
    }
    public function testgetAdminUser()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('GET', 'api/admin/users');
        $response->assertStatus(401);
    }
    public function testgetAdminTeam()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('GET', 'api/admin/teams');
        $response->assertStatus(401);
    }
    public function testgetAdminPlayer()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('GET', 'api/admin/players');
        $response->assertStatus(401);
    }
    public function testgetAdminTransfer()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/json'
        ])->json('GET', 'api/admin/transfers');
        $response->assertStatus(401);
    }
    public function tearDown() :void{
        $this->user->delete();
    }
}
