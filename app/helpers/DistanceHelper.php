<?php


if (!function_exists('get_distance')) {

    /**
     * Calcul la distance entre des coordonnées géographiques
     * Utilise l'unité kilométrique
     *
     * @param float $lat1 Latitude point 1
     * @param float $lon1 Longitude point 1
     * @param float $lat2 Latitude point 2
     * @param float $lon2 Lontitude point 2
     * @return float
     */
    function get_distance(float $lat1, float $lon1, float $lat2, float $lon2) : float {

        $radlat1 = pi() * $lat1/180;
		$radlat2 = pi() * $lat2/180;
		$theta = $lon1 - $lon2;
		$radtheta = pi() * $theta/180;     
		$dist = sin($radlat1) * sin($radlat2) + cos($radlat1) * cos($radlat2) * cos($radtheta);

		if ($dist > 1) {
			$dist = 1;         
		}

		$dist = acos($dist);
		$dist = $dist * 180 / pi();
		$dist = $dist * 60 * 1.1515;

        $dist = $dist * 1.609344;
		
        return $dist;

    }

}