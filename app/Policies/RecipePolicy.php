<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the current user can view recipes
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
     * Determine if user can create recipe
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user) : bool {

        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value,
        ]);

    }

    /**
     * Determine if a recipe can be updated
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    public function update(User $user, Recipe $recipe) : bool {

        return $this->canEditRecipe($user, $recipe);

    }

    /**
     * Determine if a recipe can be deleted by the user
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    public function delete(User $user, Recipe $recipe) : bool {

        return $this->canEditRecipe($user, $recipe);

    }


    /**
     * Determine if the user can star a recipe
     *
     * @param User $user
     * @return boolean
     */
    public function star(User $user) : bool {

        return $user->hasRole(Roles::goodfood->value);
    
    }

    /**
     * Determine if the user can unstar a recipe
     *
     * @param User $user
     * @return boolean
     */
    public function unstar(User $user) : bool {

        return $user->hasRole(Roles::goodfood->value);

    }

    /**
     * Determine if the user can attach file to recipe
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    public function attachPicture(User $user, Recipe $recipe) : bool {

        return $this->canEditRecipe($user, $recipe);

    }

    /**
     * Determine if the user can detach picture
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    public function detachPicture(User $user, Recipe $recipe) : bool {

        return $this->canEditRecipe($user, $recipe);
        

    }

    /**
     * Determine if the user can view pictures
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    public function viewPictures(User $user, Recipe $recipe) : bool {
        return true;
    }


    /**
     * Determine if the user can view ingredients
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    public function viewIngredients(User $user, Recipe $recipe) : bool {
        return true;
    }


    /**
     * Determine if an user can update a privacy recipe
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    protected function canEditRecipe(User $user, Recipe $recipe) : bool {

        if($user->hasRole(Roles::goodfood->value)) {
            return true;
        }

        if($user->hasRole(Roles::contractor->value) 
            && $recipe->isCreatedBy($user) 
            && $recipe->star === false
        ) {
            return true;
        }

        return false;

    }

}
