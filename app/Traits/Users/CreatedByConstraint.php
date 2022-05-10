<?php

namespace App\Traits\Users;

use App\Models\User;


trait CreatedByConstraint {


    /**
     * Return the relation to get the created by is linking to User
     */
    public function createdBy() {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the created_by attribute to the model from User Id or User Object
     *
     * @param User|integer $user
     * @return void
     */
    public function setCreatedBy(User|int $user) : void {
        $this->created_by = is_int($user) ? $user : $user->getAttribute($user->getKeyName());
    }

    /**
     * Determine if the user passed is the creator
     *
     * @param User|integer $user
     * @return boolean
     */
    public function isCreatedBy(User|int $user) : bool {
        
        if($user instanceof User) {
            return $user->id === $this->created_by;
        }

        return $user === $this->created_by;
    }

    /**
     * Override boot method to add automaticly the author if the user is logged 
     *
     * @return void
     */
    public static function boot() :void {
        parent::boot();

        self::creating(function ($model) {
            if($user = auth()->user()){
                $model->setCreatedBy($user);
            }
        });

    }

}