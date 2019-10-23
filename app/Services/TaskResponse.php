<?php

namespace App\Services;

use App\Models\Task;


class TaskResponse
{
    private $task;
    private $message;
    
    public function __construct(string $message, Task $task)
    {
        $this->task = $task;
        $this->message = $message;
        
    }
    
    
    public function getTask(): Task
    {
        return $this->task;
    }
    
    public function getTaskId(): int
    {
        return $this->task->id;
    }
    
    public function getTaskNum(): int
    {
        return $this->task->task;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}