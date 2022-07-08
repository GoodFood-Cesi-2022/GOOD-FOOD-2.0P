<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\Contractor;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine si l'utilisateur courant peut créer un franchisé
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user) : bool {
        return $user->hasRole(Roles::goodfood->value);
    }


    /**
     * Determine si l'utilisateur courant peut ajouter des recettes
     * au catalogue de la franchise
     *
     * @param User $user
     * @return boolean
     */
    public function addRecipes(User $user, Contractor $contractor) : bool {
        
        if($user->hasRole(Roles::goodfood->value)) {
            return true;
        }
        
        if($user->id === $contractor->owned_by) {
            return true;
        }

        return false;

    }

    /**
     * Determine si l'utilisateur courant peut récupérer la liste des franchisés
     *
     * @param User $user
     * @return boolean
     */
    public function all(User $user) : bool {

        return true;

    }

    /**
     * Determine si l'utilisateur connecté peut ajouter/modifier 
     * les horaires d'ouvertures de la franchise
     *
     * @param User $user
     * @param Contractor $contractor
     * @return boolean
     */
    public function addTimes(User $user, Contractor $contractor) : bool {

        if($user->hasRole(Roles::goodfood->value)) {
            return true;
        }
        
        if($user->id === $contractor->owned_by) {
            return true;
        }

        return false;        

    }

    /**
     * Determine si l'utilisateur courant peut récupérer les horaires 
     * de la franchise
     *
     * @param User $user
     * @param Contractor $contractor
     * @return boolean
     */
    public function viewTimes(User $user, Contractor $contractor) : bool {

        return true;

    }

    /**
     * Determine si l'utilisateur courant peut voir les recettes du franchisé
     *
     * @param User $user
     * @param Contractor $contractor
     * @return boolean
     */
    public function viewRecipes(User $user, Contractor $contractor) : bool {
        return true;
    }

    /**
     * Determine si l'utilisateur courant peut modifier les informations de liaison
     * de la recette
     *
     * @param User $user
     * @param Contractor $contractor
     * @return boolean
     */
    public function updateRecipe(User $user, Contractor $contractor) : bool {
        if($user->hasRole(Roles::goodfood->value)) {
            return true;
        }
        
        if($user->id === $contractor->owned_by) {
            return true;
        }

        return false;
    }

    /**
     * Determine si l'utilisateur courant peut supprimer du catalogue la recette
     *
     * @param User $user
     * @param Contractor $contractor
     * @param Recipe $recipe
     * @return boolean
     */
    public function deleteRecipe(User $user, Contractor $contractor, Recipe $recipe) : bool {
        
        if($user->hasRole(Roles::goodfood->value)) {
            return true;
        }
        
        if($user->id === $contractor->owned_by && !$recipe->star) {
            return true;
        }

        return false;
    }


}
