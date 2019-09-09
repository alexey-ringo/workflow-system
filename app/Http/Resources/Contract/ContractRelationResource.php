<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerRelationResource;
//use App\Http\Resources\Price\PriceResource;

class ContractRelationResource extends JsonResource
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
            'contract_num' => $this->contract_num,
            //'customer_id' => $this->customer_id,
            //'price_id' => $this->price_id,
            'is_final' => $this->is_final,
            'customer' => new CustomerRelationResource($this->customer),
            //'price' => new PriceResource($this->customer)
        ];
    }
}
