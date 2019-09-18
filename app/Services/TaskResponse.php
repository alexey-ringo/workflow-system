<?php

namespace App\Services;

use App\Models\Task;


class TaskResponse
{
    private $error = false;
    private $task;
    private $message;
    
    public function __construct(string $message, Task $task = null)
    {
        if($task) {
            $this->error = false;
        }
        else {
            $this->error = true;
        }
        $this->task = $task;
        $this->message = $message;
        
    }
    
    public function hasError(): bool
    {
        return $this->error;
    }
    
    public function getTask(): ?Task
    {
        return $this->task;
    }
    
    public function getTaskId(): int
    {
        return $this->task ? $this->task->id : 0;
    }
    
    public function getTaskNum(): int
    {
        return $this->task ? $this->task->task : 0;
    }
    
    public function getMessage(): string 
    {
        return $this->message;
    }
}