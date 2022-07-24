<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class EmailFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Limite les résultat à la valeur recherchée
     *
     * @param string $value
     * @return void
     */
    public function search(string $value) : void {

        $this->orWhere('email', 'ILIKE', "%$value%");

    }

}
