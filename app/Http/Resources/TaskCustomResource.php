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
            'id' => $this->id,
            'task' => $this->task,
            'title' => $this->title,
            'description' => $this->description,
            'sequence' => $this->sequence,
            'sequenceName' => $this->mission_name,
            'taskStatus' => $this->status,
            'prevMissionId' => $this->prevMissionId,
            'nextMissionId' => $this->nextMissionId,
            'prevMissionName' => $this->prevMissionName,
            'nextMissionName' => $this->nextMissionName,
            'isSequenceFirst' => $this->isSequenceFirst,
            'isSequenceLast' => $this->isSequenceLast
            ];
    }
}
