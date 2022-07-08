<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorTime extends Model
{
    use HasFactory;


    protected $fillable = [
        'monday_lunch_opened_at',
        'monday_lunch_closed_at',
        'monday_night_opened_at',
        'monday_night_closed_at',
        'tuesday_lunch_opened_at',
        'tuesday_lunch_closed_at',
        'tuesday_night_opened_at',
        'tuesday_night_closed_at',
        'wednesday_lunch_opened_at',
        'wednesday_lunch_closed_at',
        'wednesday_night_opened_at',
        'wednesday_night_closed_at',
        'thursday_lunch_opened_at',
        'thursday_lunch_closed_at',
        'thursday_night_opened_at',
        'thursday_night_closed_at',
        'friday_lunch_opened_at',
        'friday_lunch_closed_at',
        'friday_night_opened_at',
        'friday_night_closed_at',
        'saturday_lunch_opened_at',
        'saturday_lunch_closed_at',
        'saturday_night_opened_at',
        'saturday_night_closed_at',
        'sunday_lunch_opened_at',
        'sunday_lunch_closed_at',
        'sunday_night_opened_at',
        'sunday_night_closed_at',
    ];


}
