<?php

namespace App\Models;

use \App\Contracts\CreatedByConstraint as CreatedByConstraintContract;
use App\Traits\Users\CreatedByConstraint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model implements CreatedByConstraintContract
{
    use HasFactory, CreatedByConstraint;

    protected $fillable = [
        'first_line',
        'second_line',
        'zip_code',
        'country',
        'city',
        'lat',
        'lon'
    ];

    


}
