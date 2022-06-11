<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use App\Traits\Filterable\IncludesConstraint;
use App\Contracts\Filterable\IncludesConstraint as IncludesConstraintContract;

class RecipeFilter extends ModelFilter implements IncludesConstraintContract
{

    use IncludesConstraint;
    
    /**
     * Relation autorisées à être ajoutées par query parameters
     *
     * @var array<string>
     */
    public $allowed_includes = ['createdBy', 'pictures'];

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
