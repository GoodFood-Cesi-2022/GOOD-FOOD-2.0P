<?php

namespace App\Http\Resources;

class OrderStepResource extends JsonResource
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
            'state' => new OrderStateResource($this->orderState),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
