<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class IngredientTypeFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    /**
     * Filtre sur le champ code du type de l'ingrédient
     *
     * @param string $value
     * @return RecipeFilter
     */
    public function search(string $value) : self {

        return $this->where('code', 'ILIKE', "%$value%");

    } 

}
