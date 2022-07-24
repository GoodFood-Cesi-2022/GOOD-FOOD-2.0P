<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Str;

class IngredientType extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'code',
        'name',
        'description'
    ];

    /**
     * Set code to slug from name 
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn ($v) => $v,
            set: fn ($v) => Str::slug($v)
        );
    }

}
