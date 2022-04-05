<?php

namespace App\Models;

use App\Contracts\Users\ConfirmableToken as ConfirmableTokenInterface;
use App\Traits\Users\HasRole;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Contracts\Users\HasRole as HasRoleInterface;
use App\Traits\Users\ConfirmableToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, HasRoleInterface, ConfirmableTokenInterface
{
    use HasApiTokens, HasFactory, Notifiable, HasRole, ConfirmableToken;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'password',
        'email_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'confirmable_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Email de l'utilisateur
     */
    public function emailLogin() {
        return $this->belongsTo(\App\Models\Email::class, 'email_id', 'id');
    }

        /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            get: function($v) {
                return $this->emailLogin->email;
            }
        );
    }

    /**
     * Surchage de la methode pour récupérer le mail de vérification du built-in de Laravel
     *
     * @return string
     */
    public function getEmailForVerification() : string {
        return $this->emailLogin->email;
    }




}
