<?php

namespace App\Models;

use App\Contracts\CreatedByConstraint as CreatedByConstraintContract;
use App\Traits\Users\CreatedByConstraint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model implements CreatedByConstraintContract
{
    use HasFactory, SoftDeletes, CreatedByConstraint;

    /**
     * Mass assignable
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'allergen'
    ];


    /**
     * Retourne tous les types d'ingrédients de l'ingrédient
     */
    public function types() {
        return $this->belongsToMany(
            IngredientType::class,
            'ingredient_ingredient_types',
            'ingredient_id',
            'ingredient_type_id'
        )->withTimestamps();
    }

}
