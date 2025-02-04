<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase; // Resets database after each test

    /** @test */
    public function it_requires_name_email_and_password()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422) // Expect validation failure
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
    /** @test */
    public function it_requires_a_valid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }


    /** @test */
    public function it_registers_a_user_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'User registered successfully']);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }
}
