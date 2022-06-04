<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class RecipeFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Filtre sur le nom de la recette
     *
     * @param string $name
     * @return RecipeFilter
     */
    public function name(string $name) : RecipeFilter {

        return $this->where('name', 'ILIKE', "%$name%");

    } 


}
