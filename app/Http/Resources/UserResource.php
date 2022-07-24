<?php

namespace App\Http\Resources;


class UserResource extends JsonResource
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
            "id" => $this->id,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "phone" => $this->phone,
            "email" => $this->emailLogin->email,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "roles" => RoleResource::collection($this->whenLoaded('roles')),
            'abilities' => $this->appendAbilities($request)
        ];
    }
}
