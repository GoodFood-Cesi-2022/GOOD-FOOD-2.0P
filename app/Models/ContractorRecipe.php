<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorRecipe extends Model
{
    use HasFactory;

    /**
     * Recette liée
     */
    public function recipe() {
        return $this->belongsTo(\App\Models\Recipe::class, 'id', 'recipe_id');
    }

}
