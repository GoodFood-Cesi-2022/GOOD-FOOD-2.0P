<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

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
