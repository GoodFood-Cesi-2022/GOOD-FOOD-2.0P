<?php


if(!function_exists('get_contractor_days')) {

    /**
     * Retourne la liste des jours de la semaine
     *
     * @return \Illuminate\Support\Collection
     */
    function get_contractor_days() : \Illuminate\Support\Collection {
        return collect([
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday'
        ]);
    }

}

if(!function_exists('get_contractor_hours')) {

    /**
     * Retourne les services d'une franchise
     *
     * @return \Illuminate\Support\Collection
     */
    function get_contractor_hours() : \Illuminate\Support\Collection {
        return collect([
            'opened_at',
            'closed_at'
        ]);
    }

}

if(!function_exists('get_contractor_services')) {

    /**
     * Retourne la liste des services
     *
     * @return \Illuminate\Support\Collection
     */
    function get_contractor_services() : \Illuminate\Support\Collection {
        return collect([
            'lunch',
            'night'
        ]);
    }

}


if(!function_exists('get_contractor_service_days')) {

    /**
     * Retourne les jours heures d'un service pour une franchise
     *
     * @return \Illuminate\Support\Collection
     */
    function get_contractor_service_days() : \Illuminate\Support\Collection {

        return get_contractor_days()->crossJoin(get_contractor_services(), get_contractor_hours());

    }

}


if(!function_exists('get_contractor_service_days_array')) {

    /**
     * Retourne les jours heures d'un service pour une franchise
     *
     * @param callable $callback Permet de set la valeur des clés 
     * @return \Illuminate\Support\Collection
     */
    function get_contractor_service_days_array(callable $callback = null) : \Illuminate\Support\Collection {

        return get_contractor_days()->mapWithKeys(function($day) use($callback) {
            return  [
                $day => get_contractor_services()->mapWithKeys(function($service) use ($day, $callback) {
                    return [
                        $service => is_callable($callback) ? get_contractor_hours()->mapWithKeys(function($hour) use($day, $service, $callback) {
                            return [$hour => $callback($day, $service, $hour)];
                        }) : get_contractor_hours()
                    ];
                }),
            ];
        });

    }

}





