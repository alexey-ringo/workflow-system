<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Process;
use App\Http\Resources\Process\ProcessResource;

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
            'title' => $this->title,
            'name' => $this->name,
            'processes' => ProcessResource::collection($this->processes),
            'all_processes' => ProcessResource::collection(Process::all())
        ];
    }
}
