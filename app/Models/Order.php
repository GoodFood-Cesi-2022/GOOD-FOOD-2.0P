<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes, Filterable;


    public $fillable = [
        'amount'
    ];

    /**
     * Retourne l'utilisateur ayant commandé
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Retourne le code VAT
     */
    public function vatCode() {
        return $this->belongsTo(VatCode::class, 'vat_code_id', 'id');
    }

    /**
     * Retourne les recettes de la commande
     */
    public function recipes() {
        return $this->belongsToMany(
                Recipe::class,
                'order_recipes'
            )
            ->withPivot('id', 'quantity', 'comment', 'price_unit', 'recipe_id')
            ->withTimestamps();
    }

    /**
     * Retourne les étapes de la commande
     */
    public function steps() {
        return $this->hasMany(OrderStep::class, 'order_id', 'id')->with(['orderState'])->orderBy('created_at', 'desc');
    }

    /**
     * Retourne la franchise de la commande
     */
    public function contractor() {
        return $this->belongsTo(Contractor::class, 'contractor_id', 'id');
    }

    /**
     * Retourne l'adresse de livraison de la commande
     */
    public function address() {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }



}
