<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Customer\PhoneResource;
use App\Http\Resources\Contract\ContractResource;

class CustomerRelationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'second_name' => $this->second_name,
            'surname' => $this->surname,
            'region' => $this->region,
            'city' => $this->city,
            'street' => $this->street,
            'building' => $this->building,
            'flat' => $this->flat,
            'email' => $this->email,
            'description' => $this->description,
            'phones' => PhoneResource::collection($this->phones),
            'contracts' => ContractResource::collection($this->contracts)
        ];
    }
}
