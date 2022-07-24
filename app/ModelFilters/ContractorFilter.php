<?php 

namespace App\ModelFilters;

use App\Models\Contractor;
use EloquentFilter\ModelFilter;

class ContractorFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    /**
     * Filtre les résultats sur le nom, l'email, le n° de tel
     *
     * @param string $value
     * @return void
     */
    public function search(string $value) : void {

        $this->orWhere('name', 'ILIKE', "%$value%")
             ->orWhere('phone', 'ILIKE', "%$value%")
             ->orWhereHas('email', function(\Illuminate\Database\Eloquent\Builder $query) use ($value) : void {
                $query->where('email', 'ILIKE', "%$value%");
             });

    }


    /**
     * Restreint la liste aux franchisés pouvant livrer dans ce rayon
     *
     * @param array<string, float> $pos
     * @return void
     */
    public function pos(array $pos) : void {

        if(array_key_exists('lat', $pos) && array_key_exists('lon', $pos)) {

            $lat1 = (float) $pos['lat'];
            $lon1 = (float) $pos['lon'];

            $contractors = Contractor::with('address')->get();

            $allowed_contractor_ids = collect([]);

            foreach($contractors as $contractor) {

                $lat2 = (float) $contractor->address->lat;
                $lon2 = (float) $contractor->address->lon;

                $dist = get_distance($lat1, $lon1, $lat2, $lon2);

                if($dist <= $contractor->max_delivery_radius) {
                    $allowed_contractor_ids->push($contractor->id);
                }

            }

            $this->whereIn('id', $allowed_contractor_ids->toArray());

        }


    }

}
