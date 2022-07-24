<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'default',
        'timezone',
        'user_id',
        'address_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Retourne l'adresse associé
     */
    public function address() {
        return $this->hasOne(\App\Models\Address::class, 'id', 'address_id');
    }

}
