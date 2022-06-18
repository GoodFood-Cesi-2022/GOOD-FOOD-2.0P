<?php

namespace App\Models;

use App\Traits\Users\CreatedByConstraint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\CreatedByConstraint as CreatedByConstraintContract;

class Contractor extends Model implements CreatedByConstraintContract
{
    use HasFactory, SoftDeletes, CreatedByConstraint;


    protected $fillable = [
        'name',
        'phone',
        'timezone',
        'max_delivery_radius'
    ];

    /**
     * Email de la franchise
     */
    public function email() {
        return $this->belongsTo(\App\Models\Email::class);
    }

    /**
     * Adresse de la franchise
     */
    public function address() {
        return $this->belongsTo(\App\Models\Address::class);
    }


}
