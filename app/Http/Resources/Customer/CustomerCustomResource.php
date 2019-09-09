<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;

class CustomerCustomResource extends JsonResource
{
    public function __construct(
                                $resource, 
                                Collection $phones,
                                Collection $contracts
                                )
    {
        $this->resource = $resource;
        $this->phones = $phones;
        $this->contracts = $contracts;
    }
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'second_name' => $this->resource->second_name,
            'surname' => $this->resource->surname,
            'region' => $this->resource->region,
            'city' => $this->resource->city,
            'street' => $this->resource->street,
            'building' => $this->resource->building,
            'flat' => $this->resource->flat,
            'email' => $this->resource->email,
            'description' => $this->resource->description,
            'phones' => $this->phones,
            'contracts' => $this->contracts,
            ];
    }
}
