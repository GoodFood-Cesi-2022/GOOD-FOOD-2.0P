<?php

namespace App\Traits\Users;

use Ramsey\Uuid\Uuid;

trait ConfirmableToken {

    /**
     * Génére un unique token pour confirmer le compte de l'utilisateur
     *
     * @return string
     */
    public static function generateConfirmableToken() : string {

        return hash_hmac('SHA256', Uuid::uuid4(), config('app.key'));

    }

    /**
     * Marque le token de confirmation comme lu
     *
     * @return self
     */
    public function makeConfirmableTokenUsed() : self  {
        $this->confirmable_token = null;
        return $this;
    }

}