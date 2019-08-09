<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Mission;
use App\Http\Resources\Mission\MissionResource;

class GroupRelationResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'missions' => MissionResource::collection($this->missions),
            'all_missions' => MissionResource::collection(Mission::all())
        ];
    }
}
