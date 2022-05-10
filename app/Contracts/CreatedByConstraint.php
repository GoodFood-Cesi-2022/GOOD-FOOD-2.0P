<?php

namespace App\Contracts;

use App\Models\User;


interface CreatedByConstraint {

    
    public function createdBy();

    public function setCreatedBy(User|int $user) : void;

    public static function boot() : void;

    public function isCreatedBy(User|int $user) : bool;

}