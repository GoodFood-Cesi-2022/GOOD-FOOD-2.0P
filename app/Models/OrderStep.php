<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_state_id'
    ];

    /**
     * Retourne le status de la commande
     */
    public function orderState() {
        return $this->belongsTo(OrderState::class);
    }


}
