<?php

namespace App\Http\Resources;


class ContractorResource extends JsonResource
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
            'phone' => $this->phone,
            'email' => $this->email->email,
            'max_delivery_radius' => $this->max_delivery_radius,
            'created_by' => $this->created_by,
            'address_id' => $this->address_id,
            'owned_by' => $this->owned_by,
            'ownedBy' => new UserResource($this->ownedBy),
            'address' => new AddressResource($this->address),
            'abilities' => $this->appendAbilities($request),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
