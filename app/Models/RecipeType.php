<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'code'
    ];

}
