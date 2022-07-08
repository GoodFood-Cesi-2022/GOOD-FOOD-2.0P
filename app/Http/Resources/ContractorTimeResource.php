<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractorTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $times = get_contractor_service_days_array(function($day, $service, $hour) {
            $key = "${day}_${service}_${hour}";
            return $this->{$key};
        });

        $data = collect([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]);

        return $data->merge($times);
    }
}
