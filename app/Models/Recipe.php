<?php

namespace App\Models;

use App\Contracts\CreatedByConstraint as CreatedByConstraintContract;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Users\CreatedByConstraint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model implements CreatedByConstraintContract
{
    use HasFactory, SoftDeletes, Filterable, CreatedByConstraint;


    protected $fillable = [
        'name',
        'description',
        'base_price',
        'star',
        'available_at'
    ];

    protected $casts = [
        'available_at' => 'datetime'
    ];

    /**
     * Type de la recette
     */
    public function type() {
        return $this->belongsTo(RecipeType::class, 'recipe_type_id');
    }

    /**
     * Les ingrédients de la recette
     */
    public function ingredients() {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients');
    }


    /**
     * Scope les résultats sur les recettes disponibles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable(Builder $query) : Builder {
        return $query->whereDate('available_at', '<=', Carbon::today());
    }


    /**
     * Retourne les photos de la recette
     */
    public function pictures() {
        return $this->belongsToMany(File::class, 'recipe_pictures');
    }


}
