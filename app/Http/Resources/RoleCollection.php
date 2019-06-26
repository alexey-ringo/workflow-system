<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Role;
use App\Models\Permission;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
     
    public $collects = 'App\Http\Resources\RoleResource';
    
    public function toArray($request)
    {
        //return parent::toArray($request);
        
        return [
            'data' => $this->collection
        ];
    }
}
