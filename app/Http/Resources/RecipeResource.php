<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
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
            'star' => $this->star,
            'base_price' => $this->base_price,
            'description' => $this->description,
            'recipe_type' => new RecipeTypeResource($this->type),
            'ingredients' => new IngredientCollection($this->ingredients),
            'available_at' => $this->available_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
