<?php

namespace App\Models;

use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory, SoftDeletes, Filterable;


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


}
