<?php

namespace App\Http\Resources;

use App\Models\OrderState;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $amount = (float) $this->amount;
        $amount_vat = (float) ($this->amount * $this->vatCode->percentage / 100);
        $amount_with_vat = $amount + $amount_vat;

        return [
            'id' => $this->id,
            'amount' => $amount,
            'amount_vat' => $amount_vat,
            'amount_with_vat' => $amount_with_vat,
            'state' => new OrderStateResource($this->steps->first()->orderState),
            'vat' => new VatCodeResource($this->vatCode),
            'steps' => new OrderStepCollection($this->steps),
            'contractor' => new ContractorResource($this->contractor),
            'address' => new AddressResource($this->address),
            'recipes' => new RecipeCollection($this->recipes),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
