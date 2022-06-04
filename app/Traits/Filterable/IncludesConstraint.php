<?php

namespace App\Traits\Filterable;



trait IncludesConstraint {

    /**
     * Permet de charger les relations du model
     *
     * @param array $requested_includes
     * @return self
     */
    public function includes(array $requested_includes) : self {

        $allowed = array_intersect($this->allowed_includes, $requested_includes);

        return $this->with($allowed);
    }


}