<?php

namespace Tests\Feature\Commands;

use Tests\Feature\Api\ApiCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppInstallCommandTest extends ApiCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @group commands
     * @group install
     * @return void
     */
    public function test_app_install() : void {
        
        $this->artisan('app:install')
                ->expectsConfirmation('Are you ready to begin ?', 'yes')
                ->expectsQuestion('Set email for the admin account', 'test@test.test')
                ->expectsQuestion('Set name for the admin account', 'test')
                ->expectsQuestion('Set the firstname for the admin account', 'test')
                ->expectsQuestion('Set phone for the admin account', '0637796178')
                ->expectsQuestion('Enter the password for the admin account', 'test')
                ->expectsQuestion('Confirm the password', 'test')
                ->expectsChoice('Turn application mode to : ', 'normal', [
                    'configuration',
                    'normal'
                ])
                ->assertExitCode(0);

    }

    /**
     * Test la validation des inputs
     * 
     * @group commands
     * @group install
     * @return void
     */
    public function test_input_validation() : void {

        $this->artisan('app:install')
                ->expectsConfirmation('Are you ready to begin ?', 'yes')
                ->expectsQuestion('Set email for the admin account', 'test@ted')
                ->expectsQuestion('Set name for the admin account', 'test')
                ->expectsQuestion('Set the firstname for the admin account', 'test')
                ->expectsQuestion('Set phone for the admin account', '0637796178')
                ->expectsQuestion('Enter the password for the admin account', 'test')
                ->expectsQuestion('Confirm the password', 'deded')
                ->expectsChoice('Turn application mode to : ', 'normal', [
                    'configuration',
                    'normal'
                ])
                ->assertExitCode(0);

    }

}
