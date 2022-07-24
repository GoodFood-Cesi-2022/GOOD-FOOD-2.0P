<?php

namespace Tests\Unit;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class RoleContractTest extends TestCase
{
    /**
     * test all roles user
     *
     * @group unit
     * @return void
     */
    public function test_user_roles() : void {
        
        $user = User::factory()->create();

        $user->roles()->attach(Role::first()->id);

        $this->assertTrue($user->roles->count() === 1);

    }

    /**
     * test has role function
     *
     * @group unit
     * @return void
     */
    public function test_user_has_role() : void {

        $user = User::factory()->create();

        $role = Role::whereCode(Roles::goodfood->value)->first();

        $user->roles()->attach($role->id);

        $this->assertTrue($user->hasRole(Roles::goodfood->value));

        $this->assertFalse($user->hasRole(Roles::contractor->value));

    }

    /**
     * test has one roles function
     *
     * @group unit
     * @return void
     */
    public function test_user_has_one_role() : void {

        $user = User::factory()->create();

        $role = Role::whereCode(Roles::goodfood->value)->first();

        $user->roles()->attach($role->id);

        $this->assertTrue($user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]));

        $this->assertFalse($user->hasOneOfRoles([
            'user'
        ]));

    }

    /**
     * test has all roles function
     *
     * @group unit
     * @return void
     */
    public function test_user_has_all_roles() : void {

        $user = User::factory()->create();

        $role = Role::whereCode(Roles::goodfood->value)->first();

        $user->roles()->attach($role->id);

        $this->assertTrue($user->hasAllRoles([
            Roles::goodfood->value
        ]));

        $this->assertFalse($user->hasAllRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]));

    }


}
