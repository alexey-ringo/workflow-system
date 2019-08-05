<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskCustomResource extends JsonResource
{
    public function __construct($resource, int $prevMissionId, int $nextMissionId, string $prevMissionName, string $nextMissionName, bool $isSequenceFirst, bool $isSequenceLast)
    {
        $this->resource = $resource;
        $this->prevMissionName = $prevMissionName;
        $this->nextMissionName = $nextMissionName;
        $this->prevMissionId = $prevMissionId;
        $this->nextMissionId = $nextMissionId;
        $this->isSequenceFirst = $isSequenceFirst;
        $this->isSequenceLast = $isSequenceLast;
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
            'task' => $this->resource->task,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'sequence' => $this->resource->sequence,
            'sequenceName' => $this->resource->mission_name,
            'taskStatus' => $this->resource->status,
            'prevMissionId' => $this->prevMissionId,
            'nextMissionId' => $this->nextMissionId,
            'prevMissionName' => $this->prevMissionName,
            'nextMissionName' => $this->nextMissionName,
            'isSequenceFirst' => $this->isSequenceFirst,
            'isSequenceLast' => $this->isSequenceLast
            ];
    }
}
