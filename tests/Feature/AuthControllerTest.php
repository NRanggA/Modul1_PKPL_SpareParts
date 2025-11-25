<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_successfully()
    {
        $response = $this->post(route('register.post'), [
            'email' => 'test@example.com',
            'password' => 'passwordku1234',
            'password_confirmation' => 'passwordku1234',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    /** @test */
    public function registration_fails_if_password_too_short()
    {
        $response = $this->post(route('register.post'), [
            'email' => 'x@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('passwordku1234'),
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'admin@example.com',
            'password' => 'passwordku1234',
        ]);

        $response->assertRedirect(route('inventory.index'));

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('passwordku1234'),
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'admin@example.com',
            'password' => 'salahbanget',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout_successfully()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
