<?php

namespace App\Http\Resources;


class ContractorRecipeResource extends JsonResource
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
            'price' => $this->pivot->price,
            'recipe' => new RecipeResource($this->resource)
        ];
    }
}
