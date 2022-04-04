<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * fills
     *
     * @var array<int, string>
     */
    protected $fillable = ['code'];


    /**
     * Don't use CREATED_AT & UPDATED_AT Column
     *
     * @var boolean
     */
    public $timestamps = false;

}
