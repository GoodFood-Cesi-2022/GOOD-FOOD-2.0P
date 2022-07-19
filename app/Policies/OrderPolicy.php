<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Order;
use App\Models\Contractor;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine si l'utilisateur courant peut créer une commande
     *
     * @param User $user
     * @return void
     */
    public function create(User $user) {
        return $user->hasOneOfRoles([
            Roles::goodfood->value,
            Roles::contractor->value,
            Roles::user->value,
        ]);
    }

    /**
     * Determine si l'utilisatuer courant peut voir la commande
     *
     * @param User $user
     * @param Order $order
     * @return boolean
     */
    public function view(User $user, Order $order, Contractor $contractor) : bool {
        
        if($user->hasRole(Roles::goodfood->value)) {
            return true;
        }

        if($user->hasRole(Roles::contractor->value) && $order->contractor_id === $contractor->id){
            return true;
        }
        
        return false;

    }

    /**
     * Determine si l'utilisateur courant peut accepter la commande
     *
     * @param User $user
     * @param Order $order
     * @param Contractor $contractor
     * @return boolean
     */
    public function accept(User $user, Order $order, Contractor $contractor) : bool {
        return $this->userCanAcceptOrReject($user, $order, $contractor);
    }

    /**
     * Determine si l'utilisateur courant peut rejeter la commande
     *
     * @param User $user
     * @param Order $order
     * @param Contractor $contractor
     * @return boolean
     */
    public function reject(User $user, Order $order, Contractor $contractor) : bool {
        return $this->userCanAcceptOrReject($user, $order, $contractor);
    }


    /**
     * Determine si l'utilisateur peut accepter ou rejeter la commande
     * Uniquement le propriétaire de la franchise et si la commande vient juste d'être créée
     *
     * @param User $user
     * @param Order $order
     * @param Contractor $contractor
     * @return boolean
     */
    private function userCanAcceptOrReject(User $user, Order $order, Contractor $contractor) : bool {

        if($user->hasRole('goodfood')) {
            return true;
        }

        if($contractor->owned_by === $user->id
                && $order->steps->first()->state->code === 'creating' 
                && $order->contractor_id === $contractor->id ) {
            return true;
        }


        return false;

    }


}
