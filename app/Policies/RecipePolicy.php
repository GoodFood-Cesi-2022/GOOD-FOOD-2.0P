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

        return $this->canAttachOrDetachPicture($user, $recipe);

    }

    /**
     * Determine if the user can detach picture
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    public function detachPicture(User $user, Recipe $recipe) : bool {

        return $this->canAttachOrDetachPicture($user, $recipe);
        

    }

    /**
     * Determine if an user can attach or detach picture
     *
     * @param User $user
     * @param Recipe $recipe
     * @return boolean
     */
    protected function canAttachOrDetachPicture(User $user, Recipe $recipe) : bool {

        if($user->hasRole('goodfood')) {
            return true;
        }

        if($user->hasRole('contractor') && $recipe->isCreatedBy($user)) {
            return true;
        }

        return false;

    } 


}
