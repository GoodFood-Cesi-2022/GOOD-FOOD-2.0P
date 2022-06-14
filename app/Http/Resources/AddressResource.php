<?php

namespace App\Http\Resources;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_line' => $this->first_line,
            'second_line' => $this->second_line,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'city' => $this->city,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'abilities' => $this->appendAbilities($request)
        ];
    }
}
