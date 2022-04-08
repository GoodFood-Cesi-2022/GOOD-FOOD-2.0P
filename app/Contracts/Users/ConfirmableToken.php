<?php

namespace App\Contracts\Users;


interface ConfirmableToken {


    /**
     * Génére un unique token pour confirmer le compte de l'utilisateur
     *
     * @return string
     */
    public static function generateConfirmableToken() : string;

    /**
     * Marque le token de confirmation comme lu
     *
     * @return self
     */
    public function makeConfirmableTokenUsed() : self;

}