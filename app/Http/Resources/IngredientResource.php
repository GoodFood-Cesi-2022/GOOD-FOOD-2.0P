<?php

namespace App\Http\Resources;


class IngredientResource extends JsonResource
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
            'name' => $this->name,
            'allergen' => $this->allergen,
            'types' => IngredientTypeResource::collection($this->types),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'abilities' => $this->appendAbilities($request)
        ];
    }
}
