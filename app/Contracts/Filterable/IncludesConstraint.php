<?php

namespace App\Contracts\Filterable;


interface IncludesConstraint {

    public function includes(array $requested_includes) : self;

}