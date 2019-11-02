<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskCustomResource extends JsonResource
{
    public $resource;
    public $prevProcessName;
    public $nextProcessName;
    public $prevProcessId;
    public $nextProcessId;
    public $isSequenceFirst;
    public $isSequenceLast;
    
    public function __construct(
                                $resource, 
                                int $prevProcessId, 
                                int $nextProcessId, 
                                string $prevProcessName, 
                                string $nextProcessName, 
                                bool $isSequenceFirst, 
                                bool $isSequenceLast
                                )
    {
        $this->resource = $resource;
        $this->prevProcessName = $prevProcessName;
        $this->nextProcessName = $nextProcessName;
        $this->prevProcessId = $prevProcessId;
        $this->nextProcessId = $nextProcessId;
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
            'sequence' => $this->resource->process_sequence,
            'processName' => $this->resource->process->title,
            'taskStatus' => $this->resource->status,
            'prevProcessId' => $this->prevProcessId,
            'nextProcessId' => $this->nextProcessId,
            'prevProcessName' => $this->prevProcessName,
            'nextProcessName' => $this->nextProcessName,
            'isSequenceFirst' => $this->isSequenceFirst,
            'isSequenceLast' => $this->isSequenceLast,
            ];
    }
}
