<?php 

namespace App\ModelFilters;

use App\Contracts\Filterable\IncludesConstraint as IncludesConstraintContract;
use App\Traits\Filterable\IncludesConstraint;
use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter implements IncludesConstraintContract
{

    use IncludesConstraint;
    
    /**
     * Relation autorisées à être ajoutées par query parameters
     *
     * @var array<string>
     */
    public $allowed_includes = ['roles'];

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


}
