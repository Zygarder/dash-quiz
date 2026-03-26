<?php

namespace Tests\Feature;

use App\Models\Dasher;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;  // Reset DB between tests


    public function test_homepage_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('/');
    }

    public function test_can_create_user(): void
    {
        $response = $this->post('/api/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@test.com',
            'password' => 'joedoe',
            'password_confirmation' => 'joedoe',
        ]);     

        $response->assertRedirect();
    }

    public function test_can_login(): void
    {
        $this->actingAs(User::factory()->create ());
        // get request test
        $response = $this->post('/api/login', [
            'email' => 'johndoe@test.com',
            'password' => 'joedoe',
        ]);

        $user = Dasher::where('email', 'joedoe@test.com');

        $this->assertNotNull($user);

        // redirect test
        $response->assertRedirect();

    }
}