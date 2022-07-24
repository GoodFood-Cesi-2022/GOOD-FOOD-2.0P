<?php

namespace Tests\Feature\Auth;

use App\Models\Email;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'firstname' => 'Test User',
            'lastname' => 'lastname',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '+33637702121'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * Test la vérification de l'email avant création user
     * @return void
     */
    public function test_new_user_cant_register_with_email_allready_exists() {

        Email::create(['email' => "same@example.com"]);

        $response = $this->post('/register', [
            'firstname' => 'Test User',
            'lastname' => 'lastname',
            'email' => 'same@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '+33637702121'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);

    }

}
