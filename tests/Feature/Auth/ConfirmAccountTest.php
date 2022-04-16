<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmAccountTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test if the user see the good page
     *
     * @group confirm
     * @return void
     */
    public function test_account_confirmation_view() :void {

        $user = User::factory()->create();

        $response = $this->get('/confirm-account/' . $user->confirmable_token);

        $response->assertStatus(200)
                ->assertViewIs('auth.confirm-account')
                ->assertViewHasAll([
                    'user' => $user,
                    'token' => $user->confirmable_token,
                ]);

    }

    /**
     * Test if token doesnt exist
     *
     * @group confirm
     * @return void
     */
    public function test_not_found_if_token_doesnt_exist() : void {

        $response = $this->get('/confirm-account/poeut');

        $response->assertNotFound();

    }


    /**
     * Test if the user see the good page
     *
     * @group confirm
     * @return void
     */
    public function test_account_confirmed() :void {

        $user = User::factory()->unverified()->create();
        
        $response = $this->post('/confirm-account', [
            'password' => 'kirby1Mario@',
            'password_confirmation' => 'kirby1Mario@',
            'token' => $user->confirmable_token
        ]);

        $response->assertRedirect(config('app.front_url'));

        $user->refresh();

        $this->assertTrue(is_null($user->confirmable_token));

        $this->assertTrue(!is_null($user->email_verified_at));

    }

}