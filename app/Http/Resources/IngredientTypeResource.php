<?php

namespace App\Http\Resources;


class IngredientTypeResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'description' =>  $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'abilities' => $this->appendAbilities($request)
        ];
    }
}
