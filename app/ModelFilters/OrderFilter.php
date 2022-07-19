<?php 

namespace App\ModelFilters;

use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class OrderFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    /**
     * Filtre les commandes à partir de la date spécifiée
     *
     * @param string $date
     * @return OrderFilter
     */
    public function from(string $date) : OrderFilter {

        $translated = new Carbon($date);

        return $this->whereDate('created_at', '>=', $translated->toDateTimeString());

    }


}
