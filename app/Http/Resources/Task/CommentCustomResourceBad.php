<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentCustomResource extends JsonResource
{
    public function __construct($resource, int $processId, string $processName)
    {
        $this->resource = $resource;
        $this->missionId = $processId;
        $this->prevMissionId = $processName;
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
            'task' => $this->resource->task_num,
            'title' => $this->resource->task_seq_num,
            'description' => $this->resource->task_id,
            'sequence' => $this->resource->comment_seq,
            'sequenceName' => $this->resource->comment_seq,
            'taskStatus' => $this->resource->comment,
            'prevMissionId' => $this->resource->user_id,
            'nextMissionId' => $this->resource->user_name,
            'prevMissionName' => $this->resource->user_email,
            'nextMissionName' => $this->missionId,
            'isSequenceFirst' => $this->missionName
            ];
    }
}
