<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IngredientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can create an ingredient
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user) : bool {
        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]);
    }

    /**
     * Determine if the user can view any ingredients
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user) : bool {
        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]);
    }

    /**
     * Determine if the user can view one ingredient
     *
     * @param User $user
     * @param Ingredient $ingredient
     * @return boolean
     */
    public function view(User $user, Ingredient $ingredient) : bool {
        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value
        ]);
    }

    /**
     * Determine if the user can update the ingredient
     *
     * @param User $user
     * @param Ingredient $ingredient
     * @return boolean
     */
    public function update(User $user, Ingredient $ingredient) : bool {
        return $this->updateOrDelete($user, $ingredient);
    }

    /**
     * Determine if the user can delete the ingredient 
     *
     * @param User $user
     * @param Ingredient $ingredient
     * @return boolean
     */
    public function delete(User $user, Ingredient $ingredient) : bool {
        return $this->updateOrDelete($user, $ingredient);
    }

    /**
     * Common rule 
     *
     * @param User $user
     * @param Ingredient $ingredient
     * @return boolean
     */
    private function updateOrDelete(User $user, Ingredient $ingredient) : bool {
        if($user->hasRole(Roles::goodfood->value)) {
            return true;
        }

        if($user->hasRole(Roles::contractor->value) && $ingredient->isCreatedBy($user)) {
            return true;
        }

        return false;
    }



}
